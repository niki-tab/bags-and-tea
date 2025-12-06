<?php

namespace App\Jobs;

use App\Events\VintedScanCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\ThirdPartyServices\Vinted\Application\ScanVintedDeals;

class ScanVintedDealsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600; // 10 minutes max
    public int $tries = 1;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Log::info('Starting Vinted deal scan job (Phase 1)');

        $openaiClient = \OpenAI::client(config('services.openai.api_key'));
        $scanner = new ScanVintedDeals($openaiClient);

        $results = $scanner->scanAll(function ($message) {
            Log::info($message);
        });

        Log::info('Vinted deal scan completed (Phase 1)', $results);

        // Fire event to trigger Phase 2 (detail scraping + email notification)
        VintedScanCompleted::dispatch(
            $results['queries_processed'],
            $results['total_listings'],
            $results['new_interesting_deals'],
            $results['errors']
        );

        Log::info('VintedScanCompleted event dispatched');
    }
}
