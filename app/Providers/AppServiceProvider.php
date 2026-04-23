<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\Campaign;
use App\Models\CoinTransaction;
use App\Models\Seller;
use App\Models\User;
use App\Observers\AdminNotificationObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Register admin notification observers ──────────────────────────
        Seller::observe(AdminNotificationObserver::class);
        Campaign::observe(AdminNotificationObserver::class);
        User::observe(AdminNotificationObserver::class);
        CoinTransaction::observe(AdminNotificationObserver::class);

        // ── Share unread notification count with all views ─────────────────
        // Wrapped in try/catch so the app doesn't crash before the migration runs.
        try {
            $adminUnreadCount = AdminNotification::where('is_read', false)->count();
        } catch (\Throwable) {
            $adminUnreadCount = 0;
        }
        View::share('adminUnreadCount', $adminUnreadCount);
    }
}
