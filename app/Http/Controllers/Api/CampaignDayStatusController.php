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

class CampaignDayStatusController extends Controller
{
    private const MAX_DAY_STATUS = 7;

    /** Days after campaign end_date before completed → approved. */
    private const COMPLETED_TO_APPROVED_AFTER_DAYS = 10;

    /**
     * Resolve scraped post storage: Instagram uses tagged_posts_test, Facebook uses facebook_posts_test
     * (same as ProcessScrapeResults command).
     */
    private function scrapedPostsTable(string $sharedOn): string
    {
        return $sharedOn === 'facebook' ? 'facebook_posts_test' : 'scrapped_posts';
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
        if ($today->lt($startOfCampaignDay)) {
            return 0;
        }

        $days = (int) $startOfCampaignDay->diffInDays($today, false);

        return min(self::MAX_DAY_STATUS, max(0, $days));
    }

    /**
     * No valid scrape after campaign start: first cron → flagged, next cron still missing → deleted.
     */
    private function applyMissingPostEscalation(CampaignTransaction $transaction): void
    {
        if (in_array($transaction->status, ['completed', 'rejected', 'deleted'], true)) {
            return;
        }

        if ($transaction->status === 'flagged') {
            $transaction->status = 'deleted';

            return;
        }

        if (in_array($transaction->status, ['pending', 'active'], true)) {
            $transaction->status = 'flagged';
        }
    }

    /**
     * All rows with status completed whose end_date is at least 10 days in the past → approved.
     */
    private function approveEligibleCompletedTransactions(?int $restrictUserId): int
    {
        $cutoffDate = Carbon::now()->startOfDay()->subDays(self::COMPLETED_TO_APPROVED_AFTER_DAYS)->toDateString();

        $query = CampaignTransaction::query()
            ->where('status', 'completed')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', $cutoffDate);

        if ($restrictUserId !== null) {
            $query->where('user_id', $restrictUserId);
        }

        $promoted = 0;

        $query->orderBy('id')->chunkById(100, function ($transactions) use (&$promoted) {
            foreach ($transactions as $transaction) {
                $transaction->status = 'approved';
                $transaction->save();
                $promoted++;
            }
        });

        return $promoted;
    }

    /**
     * @return string skipped_no_code|updated|updated_absent
     */
    private function syncOneTransaction(CampaignTransaction $transaction): string
    {
        if ($transaction->unique_code === null || $transaction->unique_code === '') {
            return 'skipped_no_code';
        }

        $scrapedAtRaw = $this->latestScrapedAt($transaction->unique_code, $transaction->shared_on);
        $start = Carbon::parse($transaction->start_date)->startOfDay();

        $hasValidScrape = false;
        if ($scrapedAtRaw !== null) {
            $scrapedAt = Carbon::parse($scrapedAtRaw);
            $hasValidScrape = $scrapedAt->greaterThan($start);
        }

        if (! $hasValidScrape) {
            $this->applyMissingPostEscalation($transaction);
            $transaction->save();

            return 'updated_absent';
        }

        $today = Carbon::now()->startOfDay();
        $dayStatus = $this->calendarDayStatus($start, $today);

        $transaction->day_status = $dayStatus;

        if ($dayStatus >= self::MAX_DAY_STATUS) {
            if (! in_array($transaction->status, ['rejected', 'deleted'], true)) {
                $transaction->status = 'completed';
            }
        } elseif ($dayStatus >= 1) {
            if (! in_array($transaction->status, ['rejected', 'deleted', 'completed'], true)) {
                $transaction->status = 'active';
            }
        } elseif ($transaction->status === 'flagged') {
            $transaction->status = 'pending';
        }

        $transaction->save();

        return 'updated';
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
        return $this->syncAllTransactions(null);
    }

    private function syncAllTransactions(?int $restrictUserId): JsonResponse
    {
        $stats = [
            'processed' => 0,
            'updated' => 0,
            'updated_absent' => 0,
            'skipped_no_code' => 0,
            'errors' => [],
        ];

        $base = CampaignTransaction::query()
            ->whereNotNull('unique_code')
            ->where('unique_code', '!=', '');

        if ($restrictUserId !== null) {
            $base->where('user_id', $restrictUserId);
        }

        $base->orderBy('id')->chunkById(100, function ($transactions) use (&$stats) {
            foreach ($transactions as $transaction) {
                $stats['processed']++;
                try {
                    $outcome = $this->syncOneTransaction($transaction);
                    $stats[$this->statKeyForOutcome($outcome)]++;
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
            'message' => 'day_status sync completed.',
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

        if ($outcome === 'updated_absent') {
            $scrapedAtRaw = $this->latestScrapedAt($transaction->unique_code, $transaction->shared_on);

            return response()->json([
                'status' => true,
                'message' => 'No valid scraped post after start_date; status escalated (flagged → deleted on repeat).',
                'data' => [
                    'table' => $this->scrapedPostsTable($transaction->shared_on),
                    'start_date' => $transaction->start_date,
                    'scraped_at' => $scrapedAtRaw,
                    'day_status' => $transaction->day_status,
                    'transaction_status' => $transaction->status,
                ],
            ]);
        }

        if ($outcome === 'skipped_no_code') {
            return response()->json([
                'status' => false,
                'message' => 'Transaction has no unique_code.',
            ], 422);
        }

        $scrapedAtRaw = $this->latestScrapedAt($transaction->unique_code, $transaction->shared_on);

        return response()->json([
            'status' => true,
            'message' => 'day_status updated from scraped post.',
            'data' => [
                'unique_code' => $transaction->unique_code,
                'start_date' => $transaction->start_date,
                'scraped_at' => $scrapedAtRaw,
                'day_status' => $transaction->day_status,
                'transaction_status' => $transaction->status,
            ],
        ]);
    }
}
