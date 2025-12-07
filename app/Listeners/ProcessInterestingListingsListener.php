<?php

namespace App\Listeners;

use App\Events\VintedScanCompleted;
use App\Jobs\ProcessInterestingVintedListingsJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel;

class ProcessInterestingListingsListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(VintedScanCompleted $event): void
    {
        // Check if there are any unverified listings to process
        $unverifiedCount = VintedListingEloquentModel::where('is_interesting', true)
            ->whereNull('is_verified_product')
            ->count();

        Log::info('VintedScanCompleted event received', [
            'queries_processed' => $event->queriesProcessed,
            'total_listings' => $event->totalListings,
            'new_interesting_deals' => $event->newInterestingDeals,
            'unverified_listings' => $unverifiedCount,
        ]);

        // Dispatch phase 2 if there are unverified listings
        if ($unverifiedCount > 0) {
            ProcessInterestingVintedListingsJob::dispatch();
            Log::info('Phase 2 job dispatched', ['unverified_count' => $unverifiedCount]);
        } else {
            Log::info('No unverified listings, skipping phase 2');
        }
    }
}
