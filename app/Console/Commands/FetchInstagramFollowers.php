<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

// Not used | direct .py added in cron jobs
class FetchInstagramFollowers extends Command
{
    protected $signature = 'followers:fetch-instagram
                            {--all : Also refresh already-populated counts, not just null}
                            {--sleep=3 : Seconds to sleep between API calls to avoid rate-limiting}';

    protected $description = 'Backfill instagram_followers for verified users whose count is missing (delegates to ig_followers.py)';

    public function handle(): int
    {
        $scraperDir = base_path('scraper');
        $script     = $scraperDir . '/ig_followers.py';

        if (!file_exists($script)) {
            $this->error("ig_followers.py not found at: {$script}");
            return self::FAILURE;
        }

        $python = $this->resolvePython($scraperDir);
        $sleep  = (int) $this->option('sleep');
        $all    = $this->option('all');

        $args = "--sleep={$sleep}";
        if ($all) {
            $args .= ' --all';
        }

        $cmd = "cd " . escapeshellarg($scraperDir) . " && {$python} ig_followers.py {$args} 2>&1";

        $this->info("Running ig_followers.py via Python ({$python})...");

        passthru($cmd, $exitCode);

        return $exitCode === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function resolvePython(string $scraperDir): string
    {
        foreach ([
            $scraperDir . '/.venv/bin/python3',
            $scraperDir . '/venv/bin/python3',
        ] as $venv) {
            if (file_exists($venv)) {
                return escapeshellarg($venv);
            }
        }

        foreach (['python3.10', 'python3', 'python'] as $bin) {
            $which = trim(shell_exec("which {$bin} 2>/dev/null") ?? '');
            if ($which !== '') {
                return $bin;
            }
        }

        return 'python3';
    }
}
