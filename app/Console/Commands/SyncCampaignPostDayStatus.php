<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\CampaignDayStatusController;
use Illuminate\Console\Command;

class SyncCampaignPostDayStatus extends Command
{
    protected $signature = 'campaign:sync-post-day-status';

    protected $description = 'Sync day_status from scraped posts, escalate missing posts, promote completed to approved';

    public function handle(CampaignDayStatusController $controller): int
    {
        $this->info('Syncing campaign post day status...');

        $response = $controller->syncBulk();
        $payload = $response->getData(true);

        if (empty($payload['status'])) {
            $this->error('Sync failed or returned an unexpected response.');

            return self::FAILURE;
        }

        $data = $payload['data'] ?? [];
        $this->table(
            ['Metric', 'Count'],
            [
                ['processed', $data['processed'] ?? '—'],
                ['updated', $data['updated'] ?? '—'],
                ['updated_absent', $data['updated_absent'] ?? '—'],
                ['skipped_no_code', $data['skipped_no_code'] ?? '—'],
                ['promoted_completed_to_approved', $data['promoted_completed_to_approved'] ?? '—'],
            ]
        );

        if (! empty($data['errors'])) {
            $this->warn('Errors: '.json_encode($data['errors']));
        }

        return self::SUCCESS;
    }
}
