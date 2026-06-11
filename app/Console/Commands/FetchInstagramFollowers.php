<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\InstagramFollowerService;

class FetchInstagramFollowers extends Command
{
    protected $signature = 'followers:fetch-instagram
                            {--all : Also refresh already-populated counts, not just null}
                            {--sleep=2 : Seconds to sleep between API calls to avoid rate-limiting}';

    protected $description = 'Backfill instagram_followers for verified users whose count is missing';

    public function handle(InstagramFollowerService $service): int
    {
        $sleepSeconds = (int) $this->option('sleep');
        $refreshAll   = $this->option('all');

        $query = User::where('instagram_status', 'verified')
            ->whereNotNull('instagram_username')
            ->where('instagram_username', '!=', '');

        if (!$refreshAll) {
            $query->whereNull('instagram_followers');
        }

        $users = $query->select(['id', 'instagram_username'])->get();

        if ($users->isEmpty()) {
            $this->info('No users to update.');
            return self::SUCCESS;
        }

        $this->info("Fetching Instagram follower counts for {$users->count()} user(s)...");

        $updated = 0;
        $failed  = 0;

        foreach ($users as $user) {
            $count = $service->fetchFollowers($user->instagram_username);

            if ($count !== null) {
                User::where('id', $user->id)->update(['instagram_followers' => $count]);
                $updated++;
                $this->line("  [{$user->instagram_username}] → {$count} followers");
            } else {
                $failed++;
                $this->warn("  [{$user->instagram_username}] → failed to fetch");
            }

            if ($sleepSeconds > 0) {
                sleep($sleepSeconds);
            }
        }

        $this->info("Done. Updated: {$updated} | Failed: {$failed}");

        return self::SUCCESS;
    }
}
