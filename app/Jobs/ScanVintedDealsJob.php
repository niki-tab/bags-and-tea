<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel;

class ScanVintedDealsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 60; // Just dispatching jobs, should be quick
    public int $tries = 1;

    // Delay between each query job dispatch (in seconds)
    private const DELAY_BETWEEN_QUERIES = 5;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Log::info('Starting Vinted deal scan - dispatching individual query jobs');

        $queries = BagSearchQueryEloquentModel::where('is_active', true)->get();

        if ($queries->isEmpty()) {
            Log::info('No active queries to scan');
            return;
        }

        Log::info('Dispatching query scan jobs', ['count' => $queries->count()]);

        $delay = 0;
        foreach ($queries as $query) {
            ScanSingleVintedQueryJob::dispatch($query->id)
                ->delay(now()->addSeconds($delay));

            Log::info('Dispatched scan job for query', [
                'query_id' => $query->id,
                'name' => $query->name,
                'delay_seconds' => $delay,
            ]);

            $delay += self::DELAY_BETWEEN_QUERIES;
        }

        Log::info('All query scan jobs dispatched', [
            'total_queries' => $queries->count(),
            'total_delay_seconds' => $delay,
        ]);
    }
}
