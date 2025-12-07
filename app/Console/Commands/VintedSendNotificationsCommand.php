<?php

namespace App\Console\Commands;

use App\Jobs\SendVerifiedListingsEmailJob;
use Illuminate\Console\Command;

class VintedSendNotificationsCommand extends Command
{
    protected $signature = 'vinted:notify
                            {--sync : Run synchronously instead of dispatching to queue}';

    protected $description = 'Send email notifications for verified Vinted listings (Phase 3)';

    public function handle(): int
    {
        if ($this->option('sync')) {
            $this->info('Running notification check synchronously...');
            (new SendVerifiedListingsEmailJob())->handle();
            $this->info('Done!');
        } else {
            SendVerifiedListingsEmailJob::dispatch();
            $this->info('Notification job dispatched to queue.');
        }

        return Command::SUCCESS;
    }
}
