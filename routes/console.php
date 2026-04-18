<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Sync campaign_transactions day_status from scraped tables (cron; same as POST /api/campaign/sync-post-day-status)
Schedule::command('campaign:sync-post-day-status')
    ->dailyAt('05:00')
    ->withoutOverlapping()
    ->runInBackground();

// Check scraped post results daily and credit coins for verified 7-day campaigns
Schedule::command('campaign:process-results')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->runInBackground();

// Check scraped posts daily for pending social username verifications
Schedule::command('social:process-verifications')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->runInBackground();
