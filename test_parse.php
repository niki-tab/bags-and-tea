<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel;
use Src\ThirdPartyServices\Vinted\Application\ScanVintedDeals;

// Get Speedy 25 query
$query = BagSearchQueryEloquentModel::where("name", "like", "%Speedy 25%")->first();
echo "Testing with fixed getUrlForPage()..." . PHP_EOL;
echo "Page 1 URL: " . $query->getUrlForPage(1) . PHP_EOL;
echo "Page 2 URL: " . $query->getUrlForPage(2) . PHP_EOL;
echo PHP_EOL;

// Run the scanner
$openai = \OpenAI::client(config("services.openai.api_key"));
$scanner = new ScanVintedDeals($openai);

echo "Running scan..." . PHP_EOL;
$result = $scanner->scanQuery($query, function($msg) {
    echo "  " . $msg . PHP_EOL;
});

echo PHP_EOL;
echo "Results:" . PHP_EOL;
echo "  Pages scraped: " . $result["pages_scraped"] . PHP_EOL;
echo "  Total listings saved: " . $result["total_listings"] . PHP_EOL;
echo "  New interesting deals: " . $result["new_interesting_deals"] . PHP_EOL;
echo PHP_EOL;

// Check if the listing was saved
$listing = \Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel::where('vinted_item_id', '7732455699')->first();
if ($listing) {
    echo "✅ Listing 7732455699 NOW IN DATABASE!" . PHP_EOL;
    echo "  Title: " . $listing->title . PHP_EOL;
    echo "  Price: €" . $listing->price . PHP_EOL;
    echo "  is_interesting: " . ($listing->is_interesting ? "YES" : "NO") . PHP_EOL;
} else {
    echo "❌ Listing 7732455699 still NOT in database" . PHP_EOL;
}
