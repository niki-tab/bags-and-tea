<?php

namespace App\Listeners;

use App\Events\VintedScanCompleted;
use App\Jobs\ProcessInterestingVintedListingsJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ProcessInterestingListingsListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(VintedScanCompleted $event): void
    {
        Log::info('VintedScanCompleted event received, dispatching phase 2 job', [
            'queries_processed' => $event->queriesProcessed,
            'total_listings' => $event->totalListings,
            'new_interesting_deals' => $event->newInterestingDeals,
        ]);

        // Only process if there are new interesting deals
        if ($event->newInterestingDeals > 0) {
            ProcessInterestingVintedListingsJob::dispatch();
        } else {
            Log::info('No new interesting deals, skipping phase 2');
        }
    }
}
