<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel;

class ProcessInterestingVintedListingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 60;
    public int $tries = 2;

    // Maximum listings to process per run
    private const MAX_LISTINGS = 35;

    // Delay between each verification job (in seconds)
    private const DELAY_BETWEEN_JOBS = 30;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Log::info('Starting phase 2: Dispatching verification jobs for interesting listings');

        // Get listings that haven't been verified yet (is_verified_product IS NULL)
        $listings = VintedListingEloquentModel::where('is_interesting', true)
            ->whereNull('is_verified_product')
            ->orderBy('price', 'asc')
            ->limit(self::MAX_LISTINGS)
            ->get();

        if ($listings->isEmpty()) {
            Log::info('No listings to verify');
            return;
        }

        Log::info('Dispatching verification jobs', ['count' => $listings->count()]);

        $delay = 0;
        foreach ($listings as $listing) {
            VerifySingleVintedListingJob::dispatch($listing->id)
                ->delay(now()->addSeconds($delay));

            $delay += self::DELAY_BETWEEN_JOBS;
        }

        Log::info('All verification jobs dispatched', [
            'count' => $listings->count(),
            'total_delay_seconds' => $delay,
        ]);
    }
}
