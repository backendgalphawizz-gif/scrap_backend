<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Campaign;
use App\Models\CoinTransaction;
use App\Models\Seller;
use App\Models\User;

/**
 * Single observer class registered on Seller, Campaign, User, CoinTransaction.
 * Uses a dispatch approach since PHP does not support method overloading.
 */
class AdminNotificationObserver
{
    // ── created ───────────────────────────────────────────────────────────────

    public function created(mixed $model): void
    {
        match (true) {
            $model instanceof Seller          => $this->sellerCreated($model),
            $model instanceof Campaign        => $this->campaignCreated($model),
            $model instanceof CoinTransaction => $this->coinTransactionCreated($model),
            default                           => null,
        };
    }

    // ── updated ───────────────────────────────────────────────────────────────

    public function updated(mixed $model): void
    {
        match (true) {
            $model instanceof Seller   => $this->sellerUpdated($model),
            $model instanceof Campaign => $this->campaignUpdated($model),
            $model instanceof User     => $this->userUpdated($model),
            default                    => null,
        };
    }

    // ── Seller handlers ───────────────────────────────────────────────────────

    private function sellerCreated(Seller $seller): void
    {
        $name = trim(($seller->f_name ?? '') . ' ' . ($seller->l_name ?? ''));
        AdminNotification::fire(
            type:        'brand.registered',
            title:       'New Brand Registered',
            message:     "{$name} ({$seller->email}) just signed up and is awaiting approval.",
            link:        route('admin.brand.view', $seller->id),
            relatedId:   $seller->id,
            relatedType: 'Seller'
        );
    }

    private function sellerUpdated(Seller $seller): void
    {
        $name = trim(($seller->f_name ?? '') . ' ' . ($seller->l_name ?? ''));

        // GST submitted / put under verification
        if ($seller->isDirty('gst_status') &&
            in_array($seller->gst_status, ['Submitted', 'Under Verification'], true)) {
            AdminNotification::fire(
                type:        'brand.gst_submitted',
                title:       'Brand GST Submitted for Validation',
                message:     "{$name} submitted GST details (status: {$seller->gst_status}).",
                link:        route('admin.brand.view', $seller->id),
                relatedId:   $seller->id,
                relatedType: 'Seller'
            );
        }

        // Brand account flipped back to pending review
        if ($seller->isDirty('status') && $seller->status === 'pending') {
            AdminNotification::fire(
                type:        'brand.pending_review',
                title:       'Brand Account Pending Review',
                message:     "{$name}'s account requires admin review.",
                link:        route('admin.brand.view', $seller->id),
                relatedId:   $seller->id,
                relatedType: 'Seller'
            );
        }
    }

    // ── Campaign handlers ─────────────────────────────────────────────────────

    private function campaignCreated(Campaign $campaign): void
    {
        if ($campaign->status !== 'pending') {
            return;
        }
        $brandName = optional($campaign->brand)->username ?? 'Unknown Brand';
        AdminNotification::fire(
            type:        'brand.campaign_submitted',
            title:       'Campaign Submitted for Approval',
            message:     "{$brandName} submitted \"{$campaign->title}\" for review.",
            link:        route('admin.campaign.show', $campaign->id),
            relatedId:   $campaign->id,
            relatedType: 'Campaign'
        );
    }

    private function campaignUpdated(Campaign $campaign): void
    {
        if ($campaign->isDirty('status') && $campaign->status === 'pending') {
            $brandName = optional($campaign->brand)->username ?? 'Unknown Brand';
            AdminNotification::fire(
                type:        'brand.campaign_submitted',
                title:       'Campaign Resubmitted for Approval',
                message:     "{$brandName} resubmitted \"{$campaign->title}\" for review.",
                link:        route('admin.campaign.show', $campaign->id),
                relatedId:   $campaign->id,
                relatedType: 'Campaign'
            );
        }
    }

    // ── CoinTransaction handler ───────────────────────────────────────────────

    private function coinTransactionCreated(CoinTransaction $tx): void
    {
        if ($tx->type !== 'debit' || $tx->status !== 'pending') {
            return;
        }
        $userName = optional(optional($tx->wallet)->user)->name ?? 'A user';
        $amount   = number_format($tx->amount ?? 0, 2);
        AdminNotification::fire(
            type:        'user.upi_requested',
            title:       'UPI Withdrawal Requested',
            message:     "{$userName} requested ₹{$amount} UPI withdrawal.",
            link:        route('admin.user-wallet-transactions'),
            relatedId:   $tx->id,
            relatedType: 'CoinTransaction'
        );
    }

    // ── User handler ──────────────────────────────────────────────────────────

    private function userUpdated(User $user): void
    {
        if ($user->isDirty('pan_status') &&
            in_array($user->pan_status, ['Submitted', 'Under Verification'], true)) {
            AdminNotification::fire(
                type:        'user.pan_submitted',
                title:       'User PAN Verification Submitted',
                message:     "{$user->name} submitted PAN details for verification.",
                link:        route('admin.user.view', $user->id),
                relatedId:   $user->id,
                relatedType: 'User'
            );
        }

        if ($user->isDirty('aadhar_status') &&
            in_array($user->aadhar_status, ['Submitted', 'Under Verification'], true)) {
            AdminNotification::fire(
                type:        'user.aadhar_submitted',
                title:       'User Aadhaar Verification Submitted',
                message:     "{$user->name} submitted Aadhaar details for verification.",
                link:        route('admin.user.view', $user->id),
                relatedId:   $user->id,
                relatedType: 'User'
            );
        }
    }
}
