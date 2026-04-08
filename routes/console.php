<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

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
