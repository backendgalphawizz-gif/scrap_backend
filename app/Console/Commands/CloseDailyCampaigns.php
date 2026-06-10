<?php

namespace App\Console\Commands;

use App\Services\CampaignSettlementService;
use Illuminate\Console\Command;

class CloseDailyCampaigns extends Command
{
    protected $signature = 'campaign:close-daily-ended';

    protected $description = 'Close campaigns whose end date passed at midnight and settle eligible ones. Runs at 00:05 so campaigns end precisely at the official day boundary.';

    public function __construct(protected CampaignSettlementService $settlementService)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Closing daily-ended campaigns...');

        $closed  = $this->settlementService->closeEligibleCampaigns();
        $settled = $this->settlementService->settleEligibleCampaigns();

        $this->info("Done. Closed: {$closed} | Settled: {$settled}");
    }
}
