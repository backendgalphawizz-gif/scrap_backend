<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Close campaigns the moment the official day ends (midnight) so the end time
// is exactly 12:00 AM and does not drift to the 6 AM verification window.
Schedule::command('campaign:close-daily-ended')
    ->dailyAt('00:05')
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
