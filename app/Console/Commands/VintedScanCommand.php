<?php

namespace App\Console\Commands;

use App\Jobs\ScanVintedDealsJob;
use Illuminate\Console\Command;

class VintedScanCommand extends Command
{
    protected $signature = 'vinted:scan
                            {--sync : Run synchronously instead of dispatching to queue}';

    protected $description = 'Scan Vinted for luxury bag deals based on active search queries';

    public function handle(): int
    {
        if ($this->option('sync')) {
            $this->info('Running Vinted scan synchronously...');
            $this->runSync();
        } else {
            ScanVintedDealsJob::dispatch()
                ->onConnection('redis')
                ->onQueue('default');
            $this->info('Vinted scan job dispatched to queue.');
            $this->line('Run "php artisan horizon" or check your queue worker to process it.');
        }

        return Command::SUCCESS;
    }

    private function runSync(): void
    {
        $openaiClient = \OpenAI::client(config('services.openai.api_key'));
        $scanner = new \Src\ThirdPartyServices\Vinted\Application\ScanVintedDeals($openaiClient);

        $startTime = microtime(true);

        $results = $scanner->scanAll(function ($message) {
            $this->line($message);
        });

        $elapsed = round(microtime(true) - $startTime, 2);

        $this->newLine();
        $this->info('Scan completed in ' . $elapsed . 's');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Queries processed', $results['queries_processed']],
                ['Total listings found', $results['total_listings']],
                ['New interesting deals', $results['new_interesting_deals']],
                ['Errors', count($results['errors'])],
            ]
        );

        if ($results['new_interesting_deals'] > 0) {
            $this->newLine();
            $this->info("{$results['new_interesting_deals']} new deals found!");
        }

        if (!empty($results['errors'])) {
            $this->newLine();
            $this->error('Errors:');
            foreach ($results['errors'] as $error) {
                $this->line("  - {$error['query']}: {$error['error']}");
            }
        }
    }
}
