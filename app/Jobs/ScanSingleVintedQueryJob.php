<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\ThirdPartyServices\Vinted\Application\ScanVintedDeals;
use Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel;

class ScanSingleVintedQueryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 180; // 3 minutes per query should be enough
    public int $tries = 2;

    public function __construct(
        private readonly string $queryId
    ) {
    }

    public function handle(): void
    {
        $query = BagSearchQueryEloquentModel::find($this->queryId);

        if (!$query) {
            Log::warning('Vinted query not found', ['query_id' => $this->queryId]);
            return;
        }

        if (!$query->is_active) {
            Log::info('Skipping inactive query', ['query_id' => $this->queryId, 'name' => $query->name]);
            return;
        }

        Log::info('Starting single query scan', [
            'query_id' => $this->queryId,
            'name' => $query->name,
            'brand' => $query->brand,
        ]);

        try {
            $openaiClient = \OpenAI::client(config('services.openai.api_key'));
            $scanner = new ScanVintedDeals($openaiClient);

            $result = $scanner->scanQuery($query, function ($message) {
                Log::info($message);
            });

            Log::info('Single query scan completed', [
                'query_id' => $this->queryId,
                'name' => $query->name,
                'pages_scraped' => $result['pages_scraped'],
                'total_listings' => $result['total_listings'],
                'new_interesting_deals' => $result['new_interesting_deals'],
            ]);

            // If there are new interesting deals, dispatch Phase 2 for verification
            if ($result['new_interesting_deals'] > 0) {
                ProcessInterestingVintedListingsJob::dispatch()
                    ->delay(now()->addSeconds(10));

                Log::info('Phase 2 dispatched for new interesting deals', [
                    'query_id' => $this->queryId,
                    'new_deals' => $result['new_interesting_deals'],
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to scan query', [
                'query_id' => $this->queryId,
                'name' => $query->name,
                'error' => $e->getMessage(),
            ]);

            throw $e; // Re-throw so the job can retry
        }
    }
}
