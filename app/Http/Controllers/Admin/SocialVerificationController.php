<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SocialVerificationTransaction;
use App\Models\User;
use App\Services\InstagramFollowerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialVerificationController extends Controller
{
    public function index(Request $request)
    {
        $transactions = SocialVerificationTransaction::with(['user', 'seller'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when(!$request->filled('status'), fn($q) => $q->where('status', SocialVerificationTransaction::STATUS_PENDING))
            ->when($request->filled('platform'), fn($q) => $q->where('platform', $request->platform))
            ->when($request->filled('account_type'), function ($q) use ($request) {
                if ($request->account_type === 'user') {
                    $q->whereNotNull('user_id')->whereNull('seller_id');
                } elseif ($request->account_type === 'brand') {
                    $q->whereNotNull('seller_id');
                }
            })
            ->when($request->filled('unique_code'), fn($q) => $q->where('unique_code', 'like', '%' . trim($request->unique_code) . '%'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin-views.social-verification.index', compact('transactions'));
    }

    public function manualVerify(Request $request, $id)
    {
        $transaction = SocialVerificationTransaction::with(['user', 'seller'])->findOrFail($id);

        if ($transaction->status === SocialVerificationTransaction::STATUS_VERIFIED) {
            return back()->with('error', 'This account is already verified.');
        }

        $transaction->status             = SocialVerificationTransaction::STATUS_VERIFIED;
        $transaction->verified_at        = now();
        $transaction->manually_verified  = true;
        $transaction->manually_verified_by = auth()->id();
        $transaction->manually_verified_at = now();
        $transaction->save();

        $statusField   = $transaction->platform . '_status';
        $usernameField = $transaction->platform . '_username';

        if ($transaction->user_id) {
            $updates = [$statusField => SocialVerificationTransaction::STATUS_VERIFIED];
            if (filled($transaction->username)) {
                $updates[$usernameField] = $transaction->username;
            }

            if ($transaction->platform === 'instagram' && filled($transaction->username)) {
                try {
                    $followers = app(InstagramFollowerService::class)->fetchFollowers($transaction->username);
                    if ($followers !== null) {
                        $updates['instagram_followers'] = $followers;
                    }
                } catch (\Throwable $e) {
                    Log::warning('SocialVerificationController::manualVerify: failed to fetch instagram followers', [
                        'user_id'  => $transaction->user_id,
                        'username' => $transaction->username,
                        'error'    => $e->getMessage(),
                    ]);
                }
            }

            User::where('id', $transaction->user_id)->update($updates);
        }

        if ($transaction->seller_id) {
            Seller::where('id', $transaction->seller_id)
                ->update([$statusField => SocialVerificationTransaction::STATUS_VERIFIED]);
        }

        $name = $transaction->user->name ?? $transaction->seller->f_name ?? 'Account';
        return back()->with('success', "{$name}'s {$transaction->platform} account has been manually verified.");
    }
}
