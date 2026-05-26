# Scrap Backend — Full Project Audit

**Generated:** 2026-05-26  
**Repository:** `scrap_backend` (Laravel 11, PHP 8.2)  
**Purpose:** Campaign posting & earning platform — brands fund campaigns, users post on social media, rewards are verified via Python scraping, coins are withdrawn to UPI or spent on vouchers.

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Business Model & Actors](#2-business-model--actors)
3. [Technology Stack](#3-technology-stack)
4. [Project Structure](#4-project-structure)
5. [Core Business Flows](#5-core-business-flows)
6. [Data Model Overview](#6-data-model-overview)
7. [API & Admin Surface](#7-api--admin-surface)
8. [Configuration & Settings](#8-configuration--settings)
9. [Python Scraping Pipeline](#9-python-scraping-pipeline)
10. [Known Bugs (Prioritized)](#10-known-bugs-prioritized)
11. [Incomplete Features](#11-incomplete-features)
12. [Edge Cases & Business Logic Gaps](#12-edge-cases--business-logic-gaps)
13. [Security Concerns](#13-security-concerns)
14. [Recommended Fix Roadmap](#14-recommended-fix-roadmap)
15. [Key File Index](#15-key-file-index)

---

## 1. Executive Summary

The system is a **multi-actor marketplace** for influencer-style campaigns:

| Actor | Role |
|-------|------|
| **User** | Participates in campaigns, earns coins, withdraws to UPI, buys vouchers, gives feedback |
| **Brand (Seller)** | Creates/funds campaigns from prepaid wallet, defines feedback questions, verifies social accounts |
| **Sales person (Sale)** | Onboards brands, creates campaigns on their behalf, earns commission via referral code |
| **Admin** | Web panel: approvals, refunds (manual bank), commission approval, settings (GST/TDS/splits), reports |

**Money flow (intended):** Brand wallet → campaign budget → user coin wallet (after scrape verification + grace period) → UPI withdrawal or vouchers. Sales earns commission ledger entries approved by admin.

**Critical gaps found:**

- **Unused campaign budget is not returned to the brand wallet** when a campaign completes with partial participation (e.g. only 10 of 100 slots filled). This matches the bug you reported.
- **Wallet balance check uses pre-GST amount but debit uses GST-inclusive amount** — brands can be overdrawn or wrongly rejected.
- **Sales-created campaigns check wallet but never debit it.**
- **Social verification is not enforced** before users post (gates exist but are commented out for brands; missing for users).
- **Cron/scrape endpoints are public** — anyone can trigger reward processing.
- **Scraper DB table mismatch** — Laravel reads `scrapped_posts`; migrations only create `tagged_posts_test`.

**Verification days:** Product intent mentions **7 days**; code default is **`CAMPAIGN_VERIFICATION_DAYS=3`** (env). Grace period after campaign end is **1 day** (`GRACE_PERIOD_DAYS` in `ProcessScrapeResults`).

---

## 2. Business Model & Actors

### 2.1 User journey

1. Register/login via OTP (Laravel Passport `auth:api`).
2. (Expected) Verify Instagram/Facebook by posting a unique code; cron checks `scrapped_posts`.
3. Browse/filter campaigns (gender, city, state, interests, slots).
4. Join campaign (`shareCampaign`) with `unique_code` + platform (`instagram` / `facebook`).
5. Python scraper records post presence daily → `day_status` increments.
6. After **N verified days** (env, default 3) and **campaign end + 1 day grace**, coins move from pending → wallet.
7. Optional: submit feedback → `feedback_coin` credited immediately.
8. Withdraw coins (pending admin approval) or purchase vouchers.

### 2.2 Brand journey

1. Register/login via OTP + Bearer `auth_token` (not Passport).
2. Top up `seller_wallets` via `POST brand/wallet/create` (**no payment gateway** — balance increases on request).
3. Create campaign → full **GST-inclusive budget** debited from wallet.
4. Admin may set campaign `active` / `stopped` / etc.
5. If **stopped**, admin can initiate **bank refund** (not automatic wallet credit).

### 2.3 Sales journey

1. Login via OTP + Bearer token.
2. Register brands (`POST sale/brand/create`).
3. Create campaigns for a brand with own referral code → immediate `SaleCommissionLedger` (`campaign_budget` + optional `repeat_brand`).
4. On campaign completion, another ledger may be created (`campaign_reward`) — **potential double commission**.
5. Request withdrawal → debits `sales.balance` after admin approves ledger entries.

### 2.4 Revenue split (`payment_splits` table)

Stored per campaign at creation from global `PaymentSplit`:

- `admin_percentage`, `user_percentage`, `sales_percentage`, `feedback_percentage`, `user_referral_percentage`, `repeat_brand_percentage`

Used to compute `campaign_user_budget`, `final_reward_for_user`, `coins` (via `upi_value`), `feedback_coin`, `referral_coin`.

---

## 3. Technology Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 11, PHP 8.2 |
| User API auth | Laravel Passport (`auth:api`) |
| Brand/Sales API auth | Custom long-lived `auth_token` on `sellers` / `sales` |
| Admin auth | Session guard `admin:auth` |
| Permissions | Spatie Laravel Permission + custom sale roles |
| Activity log | Spatie Activity Log |
| Push | Firebase (FCM) via helpers |
| KYC | Nerofy API (PAN/GST) from admin/brand flows |
| Scraping | Python (Playwright, Apify) in `/scraper` |
| Scheduled jobs | `routes/console.php` — daily 06:00 / 07:00 |

---

## 4. Project Structure

```
scrap_backend/
├── app/
│   ├── Console/Commands/     # ProcessScrapeResults, ProcessSocialVerifications, SyncCampaignPostDayStatus
│   ├── CPU/                    # helpers.php, image-manager.php (global)
│   ├── Http/Controllers/       # Admin web + Api/User, Api/Seller, Api/Sale
│   ├── Models/                 # 37 Eloquent models
│   └── Services/               # FCM, ImageUpload, PanValidation
├── database/migrations/        # 93 migrations
├── routes/
│   ├── api.php                 # Mobile/API routes
│   ├── web.php                 # Admin panel
│   └── console.php             # Scheduler
├── resources/views/admin-views/  # Blade admin UI
├── scraper/                    # Python scripts (not wired to scrapped_posts in repo)
├── cron architecture.txt       # Flow diagram for process-results
└── storage/                    # Uploads, compiled views
```

**Legacy residue:** Views under `admin-views/refund--/`, `product-settings--/`, `company--/` suggest an older e-commerce admin template; not part of the campaign core but still present.

**Duplicate model path:** Both `app/Models/Campaign.php` and `app\Models\Campaign.php` exist — risk on case-sensitive Linux deployments.

---

## 5. Core Business Flows

### 5.1 Campaign creation (brand API)

**File:** `app/Http/Controllers/Api/Seller/SellerDashboardController.php` → `createCampaign`

1. Rate limit (`brand_max_campaigns_per_timeframe` / `brand_campaign_creation_timeframe_hours`).
2. **Balance check:** `total_campaign_budget > wallet_amount` ❌ *should compare `compign_budget_with_gst`*.
3. Category/subcategory validation; optional `sales_referal_code` → `sale_id`.
4. GST: `compign_budget_with_gst = budget + budget * campaign_gst_percentage / 100`.
5. Payment split applied → `coins`, `feedback_coin`, `referral_coin`, percentages stored on campaign.
6. Campaign saved; **wallet debited `compign_budget_with_gst`**; `SellerWalletHistory` debit logged.
7. **Commented out:** PAN verification, Instagram/Facebook verification before create.

### 5.2 Campaign creation (sales API)

**File:** `app/Http/Controllers/Api/Sale/DashboardController.php` → `createCampaign`

- Same budget/GST/split logic; `created_by = sales_person`.
- Checks brand wallet against `total_campaign_budget` (not GST-inclusive).
- **Does not debit brand wallet** (comment: `// here remove amount from wellert`).
- Creates `SaleCommissionLedger` immediately (`reference_type = campaign_budget`).
- Optional `repeat_brand` bonus if same sales agent brought same brand within 100 days.

### 5.3 Campaign creation (admin web)

**File:** `app/Http/Controllers/CampaignController.php` → `store`

- Full campaign fields + GST calculation.
- **No wallet debit, no wallet check** — campaigns can exist without funded wallet.

### 5.4 User participation

**File:** `app/Http/Controllers/Api/User/DashboardController.php` → `shareCampaign`

- Campaign must be `active`; slot not full; not past `end_date`.
- One row per user per platform per campaign.
- Creates `CampaignTransaction` (pending) + pending `CoinTransaction` (`campaign_reward`).
- **`end_date` on transaction** = today + `CAMPAIGN_VERIFICATION_DAYS` (default 3), not campaign end date.
- **No check** for `instagram_status` / `facebook_status` verified.
- Re-submit / update flagged post logic is **commented out**.

**Coin reward uniqueness bug:** `createPendingCampaignReward` uses `firstOrCreate` by `campaign_id` only in cron (`ProcessScrapeResults::ensurePendingRewardTransaction`), and `shareCampaign` skips second platform reward if one pending txn exists — **user posting on both IG and FB may only get one reward**.

### 5.5 Verification & coin release (cron)

**Command:** `campaign:process-results`  
**File:** `app/Console/Commands/ProcessScrapeResults.php`  
**Schedule:** Daily 06:00 (+ public HTTP trigger — see security)

```
For each open CampaignTransaction:
  → Read scrapped_posts by unique_code
  → day_status = days from start_date to scraped_at (capped at CAMPAIGN_VERIFICATION_DAYS)
  → If day_status >= max: status = approved
  → If approved AND past transaction.end_date + 1 day grace: releaseReward()
       → Credit coin_wallets.balance
       → CoinTransaction status = completed
       → Optional referral_coin to referrer (friends_code → referral_code)
       → CampaignTransaction status = completed

If past end_date and day_status == 0:
  → flagged, then deleted (reward rejected)

After all transactions:
  autoCompleteEligibleCampaigns()
  → If end_date passed OR slots full OR estimated spend >= budget:
       campaign.status = completed
       createCampaignSalesCommission() for sales agent
```

**Note:** `calculateVerifiedDays` uses **single latest scrape row**, not consecutive calendar-day proof — design assumes daily scraper runs and `scraped_at` advances.

### 5.6 Campaign completion & unused budget (YOUR REPORTED BUG)

When campaign completes with **partial slots** (e.g. 10 users, 100 required):

| Step | What happens | What should happen (typical product expectation) |
|------|----------------|--------------------------------------------------|
| Auto-complete | `status = completed` only | — |
| Brand wallet | **No credit** for unused slots/budget | Credit `(unused_slots × reward_per_user × (1+GST))` or similar |
| Admin refund | Only if admin sets `stopped` + manual bank refund flow | Auto wallet refund on `completed` OR same stopped flow |

**Refund implementation today:**

- **Only** for `status === 'stopped'`.
- `calculateRefund`: `refundable = compign_budget_with_gst - (utilized_slots × reward_per_user × (1+GST%))`.
- `processRefund` / `completeRefund`: records `CampaignRefund`, admin marks bank transfer — **does not credit `seller_wallets`**.

**Conclusion:** Partial completion leaves unused funds **locked** (already debited at create, never returned). This is the root cause of *"if only 10 people participated … isn't getting remaining amount in wallet"*.

### 5.7 Stopped campaign refund (admin)

**Files:** `CampaignController::refundPreview`, `processRefund`, `completeRefund`

- Manual two-step: pending → completed with optional `confirmed_amount`.
- Bank details snapshot from brand profile.
- Still **no automatic wallet credit** — external bank transfer assumed.

### 5.8 User withdrawal (UPI)

**File:** `UserProfileController::debitWalletCoin`

- Debits `coin_wallets.balance` immediately; creates pending debit transaction.
- **`tds` taken from client request** — not computed from `tds_percent` setting.
- Admin approves/rejects in `DashboardController` (reject refunds coins).

### 5.9 Vouchers

**File:** `Api/User/VoucherController.php`

- Purchase locks voucher code, debits wallet, creates `coin_transactions` (`voucher_purchase`).
- TDS on voucher: **0** (hardcoded).

### 5.10 Feedback rewards

**File:** `UserProfileController::submitCampaignFeedback`

- One feedback per user per campaign.
- Credits `feedback_coin` immediately (completed transaction).
- **Does not verify** user participated in campaign or completed post.
- **Does not verify** campaign ended.

### 5.11 Social verification

**User:** `verifySocial` → `social_verification_transactions` (pending)  
**Brand:** `SellerSocialVerificationController`  
**Cron:** `social:process-verifications` (daily 07:00) — reads `scrapped_posts`, 24h timeout → `not_verified`

**Enforcement gap:** `shareCampaign` does not require verified social accounts.

### 5.12 Sales commission (two triggers)

| When | Type | Amount base |
|------|------|-------------|
| Sales creates campaign | `campaign_budget` | `% of total_campaign_budget` (`sale_post_commission` setting) |
| Campaign auto-completes | `campaign_reward` | `% of (completed_txns × reward_per_user)` (`sales_percentage` on campaign) |
| Repeat brand (sales create) | `repeat_brand` | `% of budget if same brand+sales within 100 days |

Admin approves ledger → credits `sales.balance` (`SaleController::updateLedgerTransactionStatus`).

---

## 6. Data Model Overview

### Core tables

| Table | Purpose |
|-------|---------|
| `users` | End users, referral codes, social status, KYC |
| `sellers` | Brands, GST/PAN/bank, `auth_token` |
| `sales` | Sales agents, `referral_code`, `balance` |
| `campaigns` | Campaign config, budget, GST, splits, `sale_id`, `created_by` |
| `campaign_transactions` | User participation, `unique_code`, `day_status`, status lifecycle |
| `coin_wallets` / `coin_transactions` | User balances & ledger |
| `seller_wallets` / `seller_wallet_histories` | Brand prepaid wallet |
| `sale_commission_ledgers` / `sale_wallet_transactions` | Sales earnings & withdrawals |
| `campaign_refunds` | Stopped-campaign refund records |
| `payment_splits` | Global revenue split config |
| `feedbacks` / `brand_feedback_questions` | Feedback |
| `social_verification_transactions` | Social verify flow |
| `voucher_brands` / `vouchers` | Voucher marketplace |
| `business_settings` | Key-value config (GST%, TDS%, UPI rate, etc.) |
| `scrapped_posts` | **Used in production code, NO migration in repo** |
| `tagged_posts_test` | Migration exists; Python samples write here |

### Campaign transaction statuses

`pending` → `active` → `approved` → `completed`  
Failure path: `flagged` → `deleted` (reward rejected)

**Slot-occupying statuses:** pending, active, approved, completed, flagged (deleted releases slot).

---

## 7. API & Admin Surface

### 7.1 Public / unauthenticated API (`routes/api.php`)

| Endpoint | Risk |
|----------|------|
| `GET /campaign/run-process-results` | Triggers coin release — **no auth** |
| `GET /campaign/run-process-verifications` | Triggers social verify — **no auth** |
| `GET|POST /campaign/sync-post-day-status` | Bulk sync — **no auth** |
| `POST /optimize-clear` | Cache clear — weak secret header |
| OTP endpoints | OTP returned in JSON in dev (`UserAuthController`) |

### 7.2 User API (`prefix: user`, `auth:api`)

Profile, wallet, withdraw, vouchers, campaigns, feedback, social verify, support tickets.

### 7.3 Brand API (`prefix: brand`)

Auth OTP; protected: campaigns CRUD, wallet, feedback questions, social verify, refunds list.

**Issue:** `GET brand/campaign/delete/{id}` — destructive via GET.

### 7.4 Sales API (`prefix: sale`)

Auth; protected: campaigns, brands, wallet/ledger, withdrawals.

### 7.5 Admin web (`routes/web.php`, `prefix: admin`)

Dashboard, users, brands, campaigns (+ refund), sales, transactions, vouchers, categories, payment split, reports, settings, employees, roles, support.

---

## 8. Configuration & Settings

Stored in `business_settings` (key/value), accessed via `Helpers::get_business_settings()`:

| Key (examples) | Usage |
|----------------|--------|
| `campaign_gst_percentage` | Campaign budget GST |
| `tds_percent` | Exposed in user config API; **not enforced server-side on withdraw** |
| `upi_value` | Coin ↔ rupee conversion |
| `sale_post_commission` | Sales commission on campaign create |
| `brand_max_campaigns_per_timeframe` | Rate limit |
| `brand_campaign_creation_timeframe_hours` | Rate limit window |
| `campaign_guideline` | Default guidelines HTML |

**Environment:**

| Variable | Default | Notes |
|----------|---------|-------|
| `CAMPAIGN_VERIFICATION_DAYS` | 3 | Set to 7 if product requires 7-day verification |
| (none for grace) | 1 day | Hardcoded `GRACE_PERIOD_DAYS` in `ProcessScrapeResults` |

---

## 9. Python Scraping Pipeline

| Script | Purpose |
|--------|---------|
| `scraper/test_scrapy.py`, `test_scraper22.py` | Instagram (Playwright) |
| `scraper/turtur.py` | Apify tagged posts |
| `scraper/face_post.py` | Facebook |

**Mismatch:**

- Migrations create `tagged_posts_test` (no `unique_code` column in migration).
- Laravel reads **`scrapped_posts`** with `unique_code`, `scraped_at`, `post_url`.
- Production table likely created manually — **not reproducible from migrations alone**.

**Operational dependency:** Daily cron + scraper must run; if scraper fails, users get flagged/deleted after campaign end.

---

## 10. Known Bugs (Prioritized)

### P0 — Financial / data integrity

| # | Bug | Location | Impact |
|---|-----|----------|--------|
| B1 | **No wallet refund on partial campaign completion** | `ProcessScrapeResults::autoCompleteEligibleCampaigns` | Brand loses unused budget (your reported issue) |
| B2 | **Balance check vs debit mismatch** | `SellerDashboardController::createCampaign` L461 vs L612 | Check `total_campaign_budget`, debit `compign_budget_with_gst` — overdraft or false "insufficient funds" |
| B3 | **Sales createCampaign never debits wallet** | `Sale/DashboardController.php` L357 | Free campaigns or inconsistent funding |
| B4 | **Brand wallet top-up without payment** | `SellerWalletController::createWalletTransaction` | Anyone with brand token can inflate balance |
| B5 | **Admin campaign create has no wallet funding** | `CampaignController::store` | Unfunded campaigns in system |

### P1 — Rewards & commissions

| # | Bug | Location | Impact |
|---|-----|----------|--------|
| B6 | **One coin reward per campaign per user** (not per platform) | `shareCampaign`, `ensurePendingRewardTransaction` | Dual-platform participants underpaid |
| B7 | **Double sales commission** (create + complete) | Sale create + `createCampaignSalesCommission` | Sales paid twice for same campaign |
| B8 | **TDS client-controlled** | `debitWalletCoin` | User can pass `tds: 0` |
| B9 | **Feedback reward without participation** | `submitCampaignFeedback` | Feedback farming |
| B10 | **`completeRefund` does not credit seller wallet** | `CampaignController::completeRefund` | Refund only tracked, not wallet (if product expects wallet refund) |

### P1 — Security

| # | Bug | Location | Impact |
|---|-----|----------|--------|
| B11 | **Public cron HTTP endpoints** | `routes/api.php` L62-78 | Unauthorized reward processing |
| B12 | **Hardcoded optimize secret** | `routes/api.php` L39 | Cache clearing if secret leaked |
| B13 | **OTP in API response** | `UserAuthController::sendOtp` | Account takeover in non-prod / if left enabled |
| B14 | **Brand/Sale tokens stored plaintext** | `sellers.auth_token`, `sales.auth_token` | DB leak = full account access |

### P2 — Verification / scraping

| # | Bug | Location | Impact |
|---|-----|----------|--------|
| B15 | **`scrapped_posts` missing from migrations** | DB vs code | Fresh deploy breaks cron |
| B16 | **`getLatestScrapedPost` OR query bug** | `ProcessScrapeResults` L218 | Wrong scrape row matched |
| B17 | **Verification days default 3, not 7** | `env('CAMPAIGN_VERIFICATION_DAYS', 3)` | Product/docs mismatch |
| B18 | **Flagged post cannot be updated** | `shareCampaign` commented block | Users stuck after false flag |

### P2 — Code quality

| # | Bug | Location | Impact |
|---|-----|----------|--------|
| B19 | **`Sale::getTotalEarningsAttribute` always "0"** | `app/Models/Sale.php` | Wrong API data |
| B20 | **Sales campaign images `json_encode` vs brand `implode`** | Sale vs Seller dashboard | Broken image URLs on one path |
| B21 | **Duplicate `Campaign.php` model paths** | `app/Models` vs `app\Models` | Linux autoload issues |
| B22 | **`updateScrappedPosts` duplicate where** | `User/DashboardController` L336-337 | Harmless but sloppy |
| B23 | **Engagement metrics stubbed to "0"** | `Campaign` model accessors | Misleading brand analytics |

---

## 11. Incomplete Features

| Feature | Status | Evidence |
|---------|--------|----------|
| Social verify before post (user) | **Not enforced** | No check in `shareCampaign` |
| Social verify before campaign (brand) | **Disabled** | Commented block in `createCampaign` |
| PAN/KYC before campaign | **Disabled** | Commented in brand + sale create |
| Payment gateway for brand wallet | **Missing** | `createWalletTransaction` adds balance directly |
| Auto wallet refund on completed campaign | **Missing** | Only manual stopped refund |
| Wallet refund on `completeRefund` | **Missing** | Bank transfer workflow only |
| Re-submit post URL when flagged | **Commented out** | `shareCampaign` |
| Admin API permission middleware | **Commented out** | `routes/api.php` |
| Image upload admin endpoint | **501** | `web.php` |
| Payment split enforcement on spend | **Partial** | Percentages stored; admin/platform share accounting unclear |
| User level daily post limit | **Computed in API** | Not enforced in `shareCampaign` |
| Scraper → `scrapped_posts` integration | **Not in repo** | Python writes test table |
| Tests | **Minimal** | Default Laravel tests only |
| Product README | **Generic Laravel** | No project-specific docs |

---

## 12. Edge Cases & Business Logic Gaps

1. **Campaign ends with 0 participants** — Brand debited full amount; no auto refund; campaign may sit until `end_date` then complete with zero utilization.

2. **Slots full before end_date** — Auto-completes; unused **budget** (if `total_user_required × reward < total_campaign_budget`) not returned.

3. **Budget exhausted before slots full** — `budgetExhausted` uses `reward_per_user × occupied_slots` vs `total_campaign_budget` — may complete early; still no refund.

4. **User deleted after flag** — Slot released; brand already paid for slot capacity.

5. **Referral coin on every completed post** — Referrer paid per released reward, not once per referred user.

6. **Sales campaign without wallet debit** — Brand balance unchanged; users still earn if campaign goes active.

7. **Admin sets campaign active without wallet** — Possible negative business outcome depending on ops process.

8. **GST on refund calculation** — Uses `reward_per_user` × slots; may not match actual `coins` paid to users (split percentages).

9. **Feedback coins** — Separate from `feedback_percentage` budget pool; no cap tied to remaining campaign budget.

10. **Voucher purchase** — Race: two users same voucher code unless DB locking (verify in `VoucherController::purchase`).

11. **Withdrawal pending** — Balance already debited; failed admin process needs reject path (exists).

12. **Campaign transaction `end_date`** — Set to participation + N days, not campaign `end_date` — release may happen before/after campaign end inconsistently.

13. **Stopped vs completed** — Brands must rely on admin to stop and manually refund; completing normally forfeits unused wallet balance.

14. **Legacy e-commerce views** — `refund--`, order PDFs — not integrated with campaign refunds; confusion for developers.

---

## 13. Security Concerns

- Unauthenticated cron triggers (reward manipulation / DoS).
- Brand self-wallet credit without payment verification.
- OTP exposed in login API responses (remove for production).
- Weak `X-SECRET-KEY` on maintenance routes.
- No rate limiting visible on OTP endpoints.
- GET endpoints for delete campaign/account.
- TDS/trust-client amounts on withdrawal.
- `scrapped_posts` table integrity — if attacker can write rows, fake verifications possible (depends on DB access).

---

## 14. Recommended Fix Roadmap

### Phase 1 — Money correctness (urgent)

1. **On campaign `completed`:** compute unused budget and **credit `seller_wallets`** + `SellerWalletHistory` (or auto-create refund record).
2. Align wallet check: `compign_budget_with_gst <= wallet_amount` everywhere (brand + sale).
3. **Debit wallet on sales-created campaigns** (same as brand flow).
4. **Admin campaign create:** require wallet balance or mark as "unfunded / draft".

### Phase 2 — Trust & security

5. Protect cron routes (IP allowlist, `auth:sanctum`, or signed tokens).
6. Enforce social verification in `shareCampaign` and uncomment brand KYC gates.
7. Server-side TDS calculation from `tds_percent`.
8. Remove OTP from API responses in production.

### Phase 3 — Rewards fairness

9. Per-platform `CoinTransaction` (unique on `campaign_id` + platform or `transaction_id`).
10. Require campaign participation before feedback reward.
11. Re-enable flagged post URL update flow.
12. Set `CAMPAIGN_VERIFICATION_DAYS=7` in `.env` if product standard is 7 days; update `cron architecture.txt`.

### Phase 4 — Infrastructure

13. Add migration for `scrapped_posts` (columns: `unique_code`, `post_url`, `scraped_at`, `username`, platform).
14. Align Python scrapers to write `scrapped_posts`.
15. Remove duplicate `Campaign.php`; add integration tests for wallet + cron flows.

---

## 15. Key File Index

| Area | Path |
|------|------|
| Brand campaign create | `app/Http/Controllers/Api/Seller/SellerDashboardController.php` |
| Sales campaign create | `app/Http/Controllers/Api/Sale/DashboardController.php` |
| Admin campaign + refund | `app/Http/Controllers/CampaignController.php` |
| User participate | `app/Http/Controllers/Api/User/DashboardController.php` |
| Scrape / rewards cron | `app/Console/Commands/ProcessScrapeResults.php` |
| Social verify cron | `app/Console/Commands/ProcessSocialVerifications.php` |
| User wallet / withdraw / feedback | `app/Http/Controllers/Api/User/UserProfileController.php` |
| Brand wallet | `app/Http/Controllers/Api/Seller/SellerWalletController.php` |
| Vouchers | `app/Http/Controllers/Api/User/VoucherController.php` |
| Sales commission admin | `app/Http/Controllers/SaleController.php` |
| Payment split admin | `app/Http/Controllers/Admin/PaymentSplitController.php` |
| Routes | `routes/api.php`, `routes/web.php`, `routes/console.php` |
| Helpers | `app/CPU/helpers.php` |
| Cron diagram | `cron architecture.txt` |
| Scrapers | `scraper/*.py` |

---

## Appendix A — Refund formula (stopped campaigns only)

```
utilized_slots = count(transactions in pending|active|approved|completed)
utilized_raw   = utilized_slots × reward_per_user
utilized_gst   = utilized_raw × (1 + GST%)
refundable     = max(0, compign_budget_with_gst - utilized_gst)
```

**Not applied when:** `status = completed` (normal end) — this is the gap for partial participation.

---

## Appendix B — Campaign auto-complete conditions

From `ProcessScrapeResults::autoCompleteEligibleCampaigns`:

- `end_date` is in the past, **OR**
- `occupied_slots >= total_user_required`, **OR**
- `reward_per_user × occupied_slots >= total_campaign_budget`

Then: `status = completed` + optional sales `campaign_reward` commission.

**No seller wallet credit step exists.**

---

*End of audit. For implementation fixes, work through [Section 14](#14-recommended-fix-roadmap) in priority order.*
