<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampaignTransaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;
use Illuminate\Support\Facades\Log;
class CampaignDayStatusController extends Controller
{
    private const MAX_DAY_STATUS = 3;

    /**
     * Resolve scraped post storage for both platforms.
     */
    private function scrapedPostsTable(string $sharedOn): string
    {
        return 'scrapped_posts';
    }

    private function latestScrapedAt(string $uniqueCode, string $sharedOn): ?string
    {
        $table = $this->scrapedPostsTable($sharedOn);

        if (! Schema::hasTable($table)) {
            return null;
        }

        return DB::table($table)
            ->where('unique_code', $uniqueCode)
            ->orderByDesc('scraped_at')
            ->value('scraped_at');
    }

    /**
     * Calendar days elapsed since campaign start (0 on the start date; 1 the next day), capped for day_status.
     */
    private function calendarDayStatus(Carbon $startOfCampaignDay, Carbon $today): int
    {   
        log::info("Calculating calendarDayStatus with startOfCampaignDay {$startOfCampaignDay} and today {$today}.");
        if ($today->lt($startOfCampaignDay)) {
            return 0;
        }

        $days = (int) $startOfCampaignDay->diffInDays($today, false);
        log::info("Raw calendar day difference calculated as {$days}.");

        return min(self::MAX_DAY_STATUS, max(0, $days));
    }

    private function approveEligibleCompletedTransactions(?int $restrictUserId): int
    {
        // Status transitions and wallet releases are handled by campaign:process-results.
        return 0;
    }

    /**
     * Read-only diagnostics for day_status; no DB mutation is applied here.
     * All actual status transitions and day_status updates are handled by campaign:process-results.
     *
     * @return array{outcome:string,computed_day_status:int,has_valid_scrape:bool,scraped_at:?string}
     */
    private function syncOneTransaction(CampaignTransaction $transaction): array
    {
        if ($transaction->unique_code === null || $transaction->unique_code === '') {
            return [
                'outcome' => 'skipped_no_code',
                'computed_day_status' => (int) ($transaction->day_status ?? 0),
                'has_valid_scrape' => false,
                'scraped_at' => null,
            ];
        }

        $scrapedAtRaw = $this->latestScrapedAt($transaction->unique_code, $transaction->shared_on);
        $created = Carbon::parse($transaction->created_at)->startOfDay();
        $today = Carbon::now()->startOfDay();
        $hasValidScrape = false;

        if (isset($scrapedAtRaw)) {
            $scrapedAt = Carbon::parse($scrapedAtRaw);
            $hasValidScrape = $scrapedAt->greaterThan($created);
        }

        $dayStatus = $hasValidScrape ? $this->calendarDayStatus($created, $today) : 0;

        return [
            'outcome' => $hasValidScrape ? 'updated' : 'updated_absent',
            'computed_day_status' => $dayStatus,
            'has_valid_scrape' => $hasValidScrape,
            'scraped_at' => $scrapedAtRaw,
        ];
    }

    public function syncForUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'unique_code' => 'nullable|string|max:20',
        ]);

        if (! empty($validated['unique_code'])) {
            return $this->performSync($validated['unique_code'], $request->user()->id);
        }

        return $this->syncAllTransactions($request->user()->id);
    }

    /**
     * Cron / public bulk: every campaign_transactions row with unique_code, matched to scraped tables by platform.
     */
    public function syncBulk(): JsonResponse
    {
        Log::info('Initiating bulk day_status sync via API endpoint.');
        return $this->syncAllTransactions(null);
    }

    private function syncAllTransactions(?int $restrictUserId): JsonResponse
    {
        $stats = [
            'processed' => 0,
            'updated' => 0,
            'updated_absent' => 0,
            'skipped_no_code' => 0,
            'diagnostics_only' => true,
            'errors' => [],
        ];

        $base = CampaignTransaction::query()
            ->whereNotNull('unique_code')
            ->where('unique_code', '!=', '');

        

        if ($restrictUserId !== null) {
            $base->where('user_id', $restrictUserId);
        }

        $base->orderBy('id')->chunkById(100, function ($transactions) use (&$stats) {
            // print_r($transactions->pluck('id')->toArray());die;
            foreach ($transactions as $transaction) {
                $stats['processed']++;
                try {
                    $outcome = $this->syncOneTransaction($transaction);
                    $stats[$this->statKeyForOutcome($outcome['outcome'])]++;
                } catch (Throwable $e) {
                    $stats['errors'][] = [
                        'campaign_transaction_id' => $transaction->id,
                        'message' => $e->getMessage(),
                    ];
                }
            }
        });

        try {
            $stats['promoted_completed_to_approved'] = $this->approveEligibleCompletedTransactions($restrictUserId);
        } catch (Throwable $e) {
            $stats['errors'][] = [
                'campaign_transaction_id' => null,
                'message' => 'approve completed: '.$e->getMessage(),
            ];
            $stats['promoted_completed_to_approved'] = 0;
        }

        return response()->json([
            'status' => true,
            'message' => 'day_status diagnostics completed (no status mutation).',
            'data' => $stats,
        ]);
    }

    private function statKeyForOutcome(string $outcome): string
    {
        return match ($outcome) {
            'updated' => 'updated',
            'updated_absent' => 'updated_absent',
            'skipped_no_code' => 'skipped_no_code',
            default => 'skipped_no_code',
        };
    }

    private function performSync(string $uniqueCode, ?int $restrictUserId): JsonResponse
    {
        $query = CampaignTransaction::query()->where('unique_code', $uniqueCode);

        if ($restrictUserId !== null) {
            $query->where('user_id', $restrictUserId);
        }

        $transaction = $query->first();

        if (! $transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Campaign transaction not found for this unique code.',
            ], 404);
        }

        $outcome = $this->syncOneTransaction($transaction);

        try {
            $this->approveEligibleCompletedTransactions($restrictUserId);
        } catch (Throwable $e) {
            // Non-fatal; single-transaction sync already applied
        }

        $transaction->refresh();

        if ($outcome['outcome'] === 'updated_absent') {
            $scrapedAtRaw = $outcome['scraped_at'];

            return response()->json([
                'status' => true,
                'message' => 'No valid scraped post after start_date (diagnostics only).',
                'data' => [
                    'table' => $this->scrapedPostsTable($transaction->shared_on),
                    'start_date' => $transaction->start_date,
                    'scraped_at' => $scrapedAtRaw,
                    'computed_day_status' => $outcome['computed_day_status'],
                    'stored_day_status' => $transaction->day_status,
                    'transaction_status' => $transaction->status,
                ],
            ]);
        }

        if ($outcome['outcome'] === 'skipped_no_code') {
            return response()->json([
                'status' => false,
                'message' => 'Transaction has no unique_code.',
            ], 422);
        }

        $scrapedAtRaw = $outcome['scraped_at'];

        return response()->json([
            'status' => true,
            'message' => 'day_status diagnostics generated from scraped post.',
            'data' => [
                'unique_code' => $transaction->unique_code,
                'start_date' => $transaction->start_date,
                'scraped_at' => $scrapedAtRaw,
                'computed_day_status' => $outcome['computed_day_status'],
                'stored_day_status' => $transaction->day_status,
                'transaction_status' => $transaction->status,
            ],
        ]);
    }
}
