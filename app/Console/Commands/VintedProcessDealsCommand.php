<?php

namespace App\Console\Commands;

use App\Jobs\ProcessInterestingVintedListingsJob;
use Illuminate\Console\Command;

class VintedProcessDealsCommand extends Command
{
    protected $signature = 'vinted:process
                            {--sync : Run synchronously instead of dispatching to queue}';

    protected $description = 'Process interesting Vinted listings (Phase 2: scrape details + send email)';

    public function handle(): int
    {
        if ($this->option('sync')) {
            $this->info('Running Vinted process deals synchronously...');
            (new ProcessInterestingVintedListingsJob())->handle();
            $this->info('Done!');
        } else {
            ProcessInterestingVintedListingsJob::dispatch()
                ->onConnection('redis')
                ->onQueue('default');
            $this->info('Vinted process deals job dispatched to queue.');
        }

        return Command::SUCCESS;
    }
}
