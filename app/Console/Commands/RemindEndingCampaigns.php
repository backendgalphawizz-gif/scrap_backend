<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Services\FcmNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemindEndingCampaigns extends Command
{
    protected $signature = 'campaign:remind-ending-soon';

    protected $description = 'Send a push notification to brands whose campaign ends tomorrow. Runs at noon so brands are notified during business hours.';

    public function __construct(private FcmNotificationService $fcm)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $campaigns = Campaign::with('brand')
            ->whereIn('status', ['active', 'live', 'accepted'])
            ->whereDate('end_date', $tomorrow)
            ->get();

        $this->info("Found {$campaigns->count()} campaign(s) ending on {$tomorrow}.");

        $notified = 0;
        $skipped  = 0;

        foreach ($campaigns as $campaign) {
            $brand = $campaign->brand;

            if (! $brand || empty($brand->cm_firebase_token)) {
                $skipped++;
                continue;
            }

            $sent = $this->fcm->sendToSeller(
                $brand,
                'Campaign Ending Tomorrow!',
                "Your campaign \"{$campaign->title}\" is ending tomorrow. Make sure everything is in order.",
                [
                    'type'        => 'campaign_ending_soon',
                    'campaign_id' => (string) $campaign->id,
                ]
            );

            if ($sent) {
                $notified++;
                Log::info("Campaign ending reminder sent", [
                    'campaign_id' => $campaign->id,
                    'brand_id'    => $brand->id,
                ]);
            } else {
                $skipped++;
                Log::warning("Campaign ending reminder failed to send", [
                    'campaign_id' => $campaign->id,
                    'brand_id'    => $brand->id,
                ]);
            }
        }

        $this->info("Done. Notified: {$notified} | Skipped/Failed: {$skipped}");
    }
}
