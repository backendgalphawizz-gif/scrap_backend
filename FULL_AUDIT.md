# Rexarix Backend — Full Code Audit

**Project:** Rexarix — Social Campaign Posting & Earning Platform  
**Audit Date:** May 29, 2026  
**Auditor:** GitHub Copilot  

---

## Table of Contents

1. [Critical Security Bugs](#1-critical-security-bugs)
2. [Logic Bugs](#2-logic-bugs)
3. [Missing Features / Incomplete Implementation](#3-missing-features--incomplete-implementation)
4. [Admin Earnings — Are They Tracked?](#4-admin-earnings--are-they-tracked)
5. [Zero-Sales Scenario — Where Does the Money Go?](#5-zero-sales-scenario--where-does-the-money-go)
6. [Payment Split Issues](#6-payment-split-issues)
7. [Verification & Flagging Flow](#7-verification--flagging-flow)
8. [Data & Concurrency Issues](#8-data--concurrency-issues)
9. [Code Smell / Minor Bugs](#9-code-smell--minor-bugs)
10. [Summary Table](#10-summary-table)

---

## 1. Critical Security Bugs

### 🔴 BUG-01 — OTP Exposed in API Response (Production Risk)

**File:** `app/Http/Controllers/Api/User/UserAuthController.php` — `sendOtp()`

The raw OTP is returned in the JSON response body:
```php
'otp' => $otp, // REMOVE in production
```
This comment exists but the code is **still live**. Any attacker monitoring API traffic can read the OTP and bypass authentication entirely. Remove this line and integrate a real SMS gateway.

**Affected flows:** Login, Forgot Password, Signup.

---

### 🔴 BUG-02 — Auth Token Issued Before OTP Verification

**File:** `app/Http/Controllers/Api/User/UserAuthController.php` — `sendOtp()`

For `login` and `forgot_password` flows, a Passport access token is created and returned **during the OTP send step**, before the OTP is even verified:
```php
$token = $user->createToken('UserToken')->accessToken;
...
return response()->json([
    'token' => $token,  // <-- full auth token before OTP verified
]);
```
An attacker who intercepts the `/send-otp` response gets a valid bearer token without ever proving phone possession. Token must only be issued in `verifyOtp()`.

---

### 🔴 BUG-03 — `updateScrappedPosts` Has No Ownership Check

**File:** `app/Http/Controllers/Api/User/DashboardController.php` — `updateScrappedPosts()`

Any authenticated user can update the `post_url` of **any** `CampaignTransaction` by supplying any `transactions_id`:
```php
$scrapped_posts = CampaignTransaction::where('id', $transactions_id)->first();
// No check: ->where('user_id', $user->id)
```
This allows a malicious user to overwrite another user's post URL, potentially voiding legitimate verifications. Add `->where('user_id', $user->id)` to the query.

---

### 🔴 BUG-04 — OTP Brute-Force Possible (4-digit, No Rate Limiting)

**File:** `app/Http/Controllers/Api/User/UserAuthController.php` — `verifyOtp()`

OTPs are only 4 digits (1000–9999 = 9000 possibilities) and there is no attempt counter, lockout, or rate limiting. A simple loop can crack an OTP in seconds. Implement attempt counting, throttle after 3 failures, and lock for 10 minutes.

---

## 2. Logic Bugs

### 🟠 BUG-05 — Social Verification NOT Checked Before Campaign Sharing

**File:** `app/Http/Controllers/Api/User/DashboardController.php` — `shareCampaign()`

The project requirement states: "user can do posting but needs to verify his socials." However, `shareCampaign()` performs **no check** that the user's Instagram or Facebook account is verified before allowing them to join a campaign. A user with `instagram_status = 'not_submitted'` can still share on Instagram.

**Fix:** Add a guard before creating the transaction:
```php
$statusField = $request->share_on . '_status';
if ($user->$statusField !== SocialVerificationTransaction::STATUS_VERIFIED) {
    return response()->json(['status' => false, 'message' => ucfirst($request->share_on) . ' account not verified.'], 422);
}
```

---

### 🟠 BUG-06 — Feedback Coins Credited Without Checking Campaign Completion

**File:** `app/Http/Controllers/Api/User/UserProfileController.php` — `submitCampaignFeedback()`

The feedback submission endpoint awards `feedback_coin` to any authenticated user who submits feedback on any campaign. There is **no validation** that:
- The user actually participated in the campaign
- The user's participation status is `approved` or `completed`

This means any user can earn feedback coins on any campaign by calling `POST /user/submit-feedback` with any valid `campaign_id`. This is free coin exploitation.

**Fix:** Check that the user has a `CampaignTransaction` for this campaign with `status IN ('approved', 'completed')` before awarding coins.

---

### 🟠 BUG-07 — `CampaignDayStatusController::syncBulk()` Is Diagnostic-Only (Does Nothing)

**File:** `app/Http/Controllers/Api/CampaignDayStatusController.php` — `syncBulk()` / `syncOneTransaction()`

The cron command `SyncCampaignPostDayStatus` calls this controller, which explicitly states `"diagnostics only (no status mutation)"` and never writes `day_status` to the database. The actual `day_status` update happens in `ProcessScrapeResults`. Running this cron command does nothing useful except log output. The command should either be removed or repurposed.

---

### 🟠 BUG-08 — `approveEligibleCompletedTransactions()` Always Returns 0

**File:** `app/Http/Controllers/Api/CampaignDayStatusController.php` — `approveEligibleCompletedTransactions()`

```php
private function approveEligibleCompletedTransactions(?int $restrictUserId): int
{
    // Status transitions and wallet releases are handled by campaign:process-results.
    return 0;
}
```
The method body is empty — it always returns 0. The cron output table will always show `promoted_completed_to_approved: 0`, giving a false impression of monitoring.

---

### 🟠 BUG-09 — Referral Coin Not Idempotent (Double-Credit Risk)

**File:** `app/Console/Commands/ProcessScrapeResults.php` — `releaseReward()`

The referral bonus is credited with `CoinTransaction::create()` (not `firstOrCreate`). There is no uniqueness guard:
```php
$referralTransaction = CoinTransaction::create([
    'transaction_id' => 'REF-' . $transaction->id,
    ...
]);
```
If `releaseReward()` is ever called twice for the same transaction (e.g., due to a cron overlap or a race condition), the referrer gets double coins. Use `firstOrCreate` with `transaction_id = 'REF-{id}'` as the lookup key.

---

### 🟠 BUG-10 — `end_date` on Transaction Uses `env()` Directly Instead of Central Config

**File:** `app/Http/Controllers/Api/User/DashboardController.php` — `shareCampaign()`

```php
'end_date' => now()->addDays((int) (env('CAMPAIGN_VERIFICATION_DAYS', 3) ?: 3))->toDateString(),
```

`ProcessScrapeResults` uses `getMaxVerifiedDays()` which reads `env('CAMPAIGN_VERIFICATION_DAYS', 3)`. These two are now consistent, but if you ever switch to a database-backed setting, `shareCampaign` will still bake the old env value into transaction rows. Centralise via a `Helpers::get_business_settings()` call or a shared service method.

---

### 🟠 BUG-11 — `GRACE_PERIOD_DAYS` Hardcoded in Two Places

`ProcessScrapeResults::GRACE_PERIOD_DAYS = 1` (private const) and `CampaignSettlementService::GRACE_PERIOD_DAYS = 1` (public const) are separate definitions. If you need to change the grace period, you must update both files. There is also no `BusinessSetting` for it, so it cannot be changed without a deploy. Move to a single `BusinessSetting` key (like `verification_grace_period_days`).

---

### 🟠 BUG-12 — `PaymentSplit` Default Values Don't Sum to 100%

**File:** `app/Http/Controllers/Admin/PaymentSplitController.php` — `edit()`

The auto-created default split is:
```
user: 45 + sales: 20 + admin: 30 + feedback: 2 + repeat_brand: 0 + user_referral: 0 = 97%
```
This is 3% short of 100. The validation correctly enforces `== 100`, so if anyone tries to save these defaults they'll get a validation error. The defaults should be corrected so the auto-seeded row is valid. Suggested fix: `user:48, sales:20, admin:20, feedback:2, repeat_brand:5, user_referral:5 = 100`.

---

### 🟠 BUG-13 — Campaign Status `'live'` vs `'active'` Inconsistency

Multiple places in the code treat `'active'` and `'live'` as separate statuses (e.g., `shouldCloseForEnrollment` eligible statuses include both), but `DashboardController::localForVocal` and `DashboardController::index` only filter `where('status', 'active')`. Campaigns with status `'live'` will be invisible to users. Clarify whether `'live'` and `'active'` are the same thing or document the distinction.

---

### 🟠 BUG-14 — Wallet Balance Deducted Outside DB Transaction in `debitWalletCoin`

**File:** `app/Http/Controllers/Api/User/UserProfileController.php` — `debitWalletCoin()`

```php
$wallet->balance -= $request->coins;
$wallet->save();
// If this create() throws...
$transaction = $wallet->transactions()->create([...]);
```
If the `CoinTransaction::create()` call fails after the wallet balance has been deducted, the user loses coins with no corresponding debit record. Wrap both operations in `DB::transaction()`.

---

## 3. Missing Features / Incomplete Implementation

### 🟡 MISSING-01 — No SMS Gateway Integration

**File:** `app/Http/Controllers/Api/User/UserAuthController.php` — `sendOtp()`

The comment `// 🔹 Here integrate SMS API (Fast2SMS / MSG91 etc)` exists but the OTP is never actually sent via SMS. Users cannot receive OTPs on their phones in production.

---

### 🟡 MISSING-02 — `verifySocial` Does Not Require `unique_code`

**File:** `app/Http/Controllers/Api/User/UserProfileController.php` — `verifySocial()`

The `unique_code` field in the `SocialVerificationTransaction` record is populated from `$request->unique_code` but the validation rules do not mark it as `required`. A user can initiate social verification without ever having a unique code to post, making the verification unverifiable:
```php
$validator = Validator::make($request->all(), [
    'platform' => 'required|in:instagram,facebook',
    'username' => 'required|string|max:100',
    // 'unique_code' is NOT required
]);
```

---

### 🟡 MISSING-03 — No Campaign Verification Completion Gate for Posting

Users can submit a campaign post even if a campaign is `'live'` (not `'active'`). The `shareCampaign()` endpoint only checks `$campaign->status != 'active'` — but this means campaigns in `'live'`, `'paused'`, `'accepted'` statuses are blocked from user participation, while `'live'` should be open. The status machine needs clearer documentation or a unified check.

---

### 🟡 MISSING-04 — No Notification When Flagged Post Is Deleted

**File:** `app/Console/Commands/ProcessScrapeResults.php` — `markDeleted()`

There IS a FCM push notification in `markFlagged()` and `markDeleted()`. However, `markDeleted()` calls `User::find($transaction->user_id)` **twice** — once for the coin transaction rejection and once for the FCM push. This is a minor N+1 but more importantly, if the first `User::find()` is `null`, the activity log call `Helpers::logUserWalletTransaction('rejected', ...)` will receive a null user, which may throw exceptions depending on the Helpers implementation.

---

### 🟡 MISSING-05 — Admin Earnings Dashboard Is a Legacy Stub

**File:** `resources/views/admin-views/report/admin-earning--.blade.php`

This blade file references variables from an older e-commerce system (`$earning_data`, `$payment_data`, `BackEndHelper::set_symbol()`, `BackEndHelper::usd_to_currency()` etc.) that **do not exist** in the current Rexarix codebase. There is no route pointing to this view. If a route is added in the future it will throw `Undefined variable` errors. Either delete this file or rewrite it for the Rexarix context.

---

## 4. Admin Earnings — Are They Tracked?

### ❌ Admin earnings are calculated on paper but NOT persisted anywhere.

**What exists:**
- `campaigns.admin_percentage` — stores the admin's cut percentage when a campaign is created.
- `ReportController::financialReport()` — calculates admin's projected share as `(baseAmount * adminPercentage) / 100` per campaign and shows a total.
- `ReportController::index()` (brand report) — same projected calculation per campaign.

**What is missing:**
- There is **no `AdminWallet` model or table**.
- There is **no `AdminEarningTransaction` or similar ledger** that records when admin actually earns money (e.g., when a campaign is settled, or when user withdrawals are processed).
- Admin earnings shown in the report are purely **projected totals based on campaign budgets**, not **actual earned/received amounts**.

**Consequence:** The admin cannot see how much they have actually earned from completed campaigns vs. campaigns that were refunded or never ran. The "admin" column in reports includes budget from stopped/refunded campaigns.

**Recommendation:** Create an `admin_earnings` table (or a dedicated ledger type) and credit a row when:
1. A campaign is settled (`CampaignSettlementService::settle()`)
2. The admin approves a user withdrawal (TDS becomes admin income)

---

## 5. Zero-Sales Scenario — Where Does the Money Go?

### ❌ On zero sales, 100% of the budget goes back to the Brand — Admin gets nothing.

**Flow when a campaign has 0 completed posts:**

1. `CampaignSettlementService::calculateReleasableAmount()` computes:
   ```
   utilizedTaxable = completedCount * rewardPerUser = 0 * x = 0
   taxableReversal = taxablePaid - 0 = taxablePaid (full budget)
   releasableAmount = full budget (returned to brand)
   ```
2. `settle()` credits the full `releasableAmount` to `SellerWallet` (the brand's wallet).
3. No split is made: admin gets 0, sales gets 0.

**Is this correct?**
- If the campaign ran and nobody posted, you could argue it's fair to return everything to the brand.
- But if the admin provides platform services regardless of participation, the admin's percentage should still be kept.
- Currently the system makes **no distinction** between "campaign was rejected before running" (full refund appropriate) vs. "campaign ran but got no posts" (admin should keep platform fee).

**What happens when admin rejects a campaign?** In `CampaignController::status()`, when `status = 'rejected'` and `created_by = 'brand'`, the full `compign_budget_with_gst` is refunded to the brand. This is correct for a rejection.

**Recommendation:** Define a business rule for the "ran but zero posts" scenario. If admin should keep their percentage, modify `calculateReleasableAmount()` to deduct admin's share before returning the rest to the brand.

---

## 6. Payment Split Issues

### Summary of payment split components

| Field | Purpose | Default (as coded) |
|---|---|---|
| `user_percentage` | User's share of `reward_per_user` | 45% |
| `sales_percentage` | Salespeople commission base | 20% |
| `admin_percentage` | Admin revenue share | 30% |
| `feedback_percentage` | Feedback coin budget | 2% |
| `repeat_brand_percentage` | Repeat brand discount | 0% |
| `user_referral_percentage` | Referral bonus budget | 0% |
| **Total** | Must = 100% | **97% ← BUG** |

The project description states user: 48%, referral: 2%, admin: 20%. The `sales_percentage` covers the salesperson commission (separate from referral). The defaults need to be corrected to sum to 100%.

### Missing: `referral_coin` is calculated from `user_referral_percentage` but it comes out of `reward_per_user` (user's gross), not from the total campaign budget. This means referral bonuses reduce user earnings rather than coming from a separate budget line. Verify this is the intended design.

---

## 7. Verification & Flagging Flow

### The flow as implemented:

```
Day 0:   User shares → CampaignTransaction (status=pending, end_date=today+3)
Cron:    Scraper finds post    → day_status++, status=active (if 0 < days < 3)
Cron:    day_status >= 3       → status=approved
Cron:    end_date passed, day_status=0, status NOT flagged → markFlagged()
Cron:    end_date passed, day_status=0, status=FLAGGED     → markDeleted()
Cron:    approved + grace passed → releaseReward() → status=completed
```

### Issues found in this flow:

**7.1 — Grace period after verification completion is per-transaction, not per-campaign end**

`canReleaseReward()` checks `transaction.end_date + GRACE_PERIOD_DAYS`. Since `transaction.end_date = join_date + CAMPAIGN_VERIFICATION_DAYS`, the grace period starts from when the user joined, not from when the campaign ends. A user who joined on day 1 of the campaign can have their reward released while the campaign is still running. The requirement says "dynamic grace period after verification completed" — this seems like it should be after the campaign ends, not after the transaction's verification window.

**7.2 — "Second deletion from flagged the very next day" is cron-dependent**

When a post gets flagged on the same cron run that marks `end_date` as past, the post is **not** deleted on the same run (correct). It will be deleted on the **next** cron run (assuming the cron runs daily). If the cron runs more or less frequently, "next day" deletion may not be "the next calendar day". This is fine for daily crons but should be documented.

**7.3 — Flagged user can add a new post URL via `updateScrappedPosts`**

Per requirements, "if post gets deleted within verification first it's marked flagged (user can add link)". The `updateScrappedPosts` endpoint lets flagged users update the `post_url`. However the endpoint has no status check — it updates regardless of current status. Also, updating `post_url` does NOT reset `day_status` or re-trigger the scraper, so the user's new link won't actually be rescraped unless the scraper re-finds it. The scraper currently matches by `unique_code`, not `post_url`. Ensure the scraper will pick up the new URL.

---

## 8. Data & Concurrency Issues

### 🔵 BUG-15 — Withdrawal `transaction_id` Is `time()` (Collision Risk)

**File:** `app/Http/Controllers/Api/User/UserProfileController.php` — `debitWalletCoin()`

```php
'transaction_id' => (string) time(),
```

Two simultaneous withdrawals (same second) will produce the same `transaction_id`. Use `uniqid('WD-', true)` or `Str::uuid()`.

---

### 🔵 BUG-16 — Slots Check Is Not Atomic (Race Condition)

**File:** `app/Http/Controllers/Api/User/DashboardController.php` — `shareCampaign()`

The slot availability is checked:
```php
if ($this->isCampaignSlotFull($campaign)) { return error; }
// gap here — another request can sneak in
CampaignTransaction::create([...]);
```
Two concurrent requests for the last slot will both pass the check and both create transactions, over-filling the campaign. Use `DB::transaction()` with a `lockForUpdate()` on the campaign row, or use an atomic counter.

---

### 🔵 BUG-17 — Duplicate `CoinTransaction` on Re-join After Deletion

**File:** `app/Http/Controllers/Api/User/DashboardController.php` — `createPendingCampaignReward()`

Currently, once a transaction is `deleted`, the user cannot re-join (the controller returns early if any transaction exists). But `createPendingCampaignReward()` uses `campaign_id + transaction_type` as its deduplication key. If re-joining is ever enabled (the commented-out code block), the old rejected `CoinTransaction` would block a new one from being created, or the old rejected one would be returned as "current" pending reward.

---

## 9. Code Smell / Minor Bugs

| ID | Location | Issue |
|---|---|---|
| S01 | `DashboardController::updateScrappedPosts()` | `->where('id', $transactions_id)->where('id', $transactions_id)` — duplicate where clause |
| S02 | `UserAuthController::verifyOtp()` | Token is named `AdminToken` but issued for regular users |
| S03 | `UserAuthController::sendOtp()` | `$token = ""` initialized but only populated for login/forgot — returned unconditionally in response |
| S04 | `ProcessScrapeResults::markDeleted()` | `User::find($transaction->user_id)` called twice — should be cached in a variable |
| S05 | `CampaignDayStatusController` | `Log::info("Sync completed log by Haqdddd.")` — debug log with personal name leaked into production code |
| S06 | `SyncCampaignPostDayStatus` | Calls controller method via `app(CampaignDayStatusController::class)->syncBulk()` — artisan commands should not call HTTP controllers directly; use a service |
| S07 | `CampaignController::store()` | `$campaign->coins` is set twice — first to `$request->reward_per_user`, then overwritten to `$final_reward_for_user / $upi_value`. The first assignment is dead code |
| S08 | `UserAuthController::register()` | `'role_id' => 1` hardcoded with comment "Default to Supervisor" — inconsistent with the user role setup |
| S09 | `CoinWallet::getTodaysCoinEarningAttribute()` | Uses `date('Y-m-d 23:58:00')` as end-of-day — misses 2 minutes of earnings every day. Use `date('Y-m-d 23:59:59')` |
| S10 | `ProcessSocialVerifications` | `findUniqueCodeInScrapedPosts()` does not filter by `platform` or `username` — any unique_code found in scrapped_posts counts as verified regardless of platform |
| S11 | API route | `GET /campaign/run-process-results` and `GET /campaign/run-process-verifications` are publicly accessible (no auth middleware) — anyone can trigger them |
| S12 | API route | `POST /api/optimize-clear` uses a hardcoded secret key `'my_secure_key_123'` in source code — this should be in `.env` |

---

## 10. Summary Table

| # | Severity | Category | Issue |
|---|---|---|---|
| BUG-01 | 🔴 Critical | Security | OTP exposed in API response |
| BUG-02 | 🔴 Critical | Security | Token issued before OTP verified |
| BUG-03 | 🔴 Critical | Security | `updateScrappedPosts` has no ownership check |
| BUG-04 | 🔴 Critical | Security | OTP brute-force, no rate limiting |
| BUG-05 | 🟠 High | Logic | Social verification not checked before campaign share |
| BUG-06 | 🟠 High | Logic | Feedback coins awarded without campaign participation check |
| BUG-07 | 🟠 High | Logic | `syncBulk` cron is diagnostic-only, no DB mutations |
| BUG-08 | 🟠 High | Logic | `approveEligibleCompletedTransactions` always returns 0 |
| BUG-09 | 🟠 High | Logic | Referral coin not idempotent — double-credit risk |
| BUG-10 | 🟠 High | Config | `end_date` uses `env()` directly, not centralised setting |
| BUG-11 | 🟠 Medium | Config | `GRACE_PERIOD_DAYS` hardcoded in two separate places |
| BUG-12 | 🟠 High | Config | Payment split defaults sum to 97% (not 100%) |
| BUG-13 | 🟠 Medium | Logic | `'live'` vs `'active'` status inconsistency hides campaigns |
| BUG-14 | 🟠 High | Data | Wallet deducted outside DB transaction in `debitWalletCoin` |
| MISSING-01 | 🟡 High | Feature | No SMS gateway — OTP never sent to phone |
| MISSING-02 | 🟡 Medium | Feature | `verifySocial` does not require `unique_code` |
| MISSING-03 | 🟡 Medium | Feature | Status gate for campaign sharing is unclear (`live` blocked) |
| MISSING-04 | 🟡 Low | Feature | Double `User::find()` in `markDeleted()` — possible null crash |
| MISSING-05 | 🟡 Medium | Feature | Admin earning blade is a legacy stub — will throw errors if routed |
| ADMIN-01 | ❌ Missing | Feature | Admin earnings are not tracked in any wallet or ledger |
| ADMIN-02 | ❌ Missing | Feature | Financial report shows projected earnings, not actual received amounts |
| ZERO-SALES | ❌ Missing | Business Logic | On zero sales, 100% budget returns to brand — admin keeps nothing |
| BUG-15 | 🔵 Medium | Data | Withdrawal `transaction_id = time()` — collision possible |
| BUG-16 | 🔵 High | Concurrency | Slot check not atomic — over-filling race condition |
| BUG-17 | 🔵 Low | Data | Re-join after deletion would conflict with old CoinTransaction |
| S01–S12 | ⚪ Low | Code Smell | See Section 9 |

---

## Recommended Fix Priority

### Immediate (before production launch)
1. **BUG-01** — Remove OTP from API response, integrate real SMS gateway.
2. **BUG-02** — Move token generation to `verifyOtp()` only.
3. **BUG-03** — Add `where('user_id', $user->id)` to `updateScrappedPosts`.
4. **BUG-04** — Add OTP attempt throttling / rate limiting.
5. **BUG-06** — Add participation + status guard to `submitCampaignFeedback`.
6. **BUG-14** — Wrap `debitWalletCoin` balance deduction in `DB::transaction()`.
7. **BUG-12** — Fix payment split defaults to sum to 100%.
8. **S11** — Add auth middleware to cron trigger API routes.
9. **S12** — Move optimize-clear secret key to `.env`.

### Short-term
10. **BUG-05** — Enforce social verification check before `shareCampaign`.
11. **BUG-09** — Make referral coin credit idempotent (`firstOrCreate`).
12. **BUG-15** — Replace `time()` with `Str::uuid()` for `transaction_id`.
13. **BUG-16** — Wrap slot check + transaction create in `DB::transaction()` with `lockForUpdate`.
14. **ADMIN-01** — Create `admin_earnings` ledger, credit on settlement.
15. **ZERO-SALES** — Define and implement business rule for zero-participation campaigns.

### Medium-term
16. **BUG-11** — Move `GRACE_PERIOD_DAYS` to a `BusinessSetting`.
17. **BUG-13** — Clarify `'live'` vs `'active'` status semantics.
18. **MISSING-02** — Make `unique_code` required in `verifySocial`.
19. **S10** — Filter `scrapped_posts` by platform in `ProcessSocialVerifications`.
20. **MISSING-05** — Delete or rewrite the legacy admin-earning blade.
