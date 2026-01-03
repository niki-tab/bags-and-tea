<?php

use App\Mail\TestEmail;
use App\Mail\OrderConfirmation;
use App\Events\NewOrderCreated;
use App\Jobs\TestHorizonJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*
Route::group(['prefix' => 'auth'], function () {

    Route::middleware(['auth:sanctum'])->group(function (){

        

    });

    Route::post('/users/generate-auth-token/{userId}', [Src\Authorization\Users\Infrastructure\PostGenerateAuthTokenByUserId::class, '__invoke']);

});


Route::group(['prefix' => 'blog'], function () {

    //Route::middleware(['auth:sanctum'])->group(function (){

        Route::get('/articles/all', [Src\Blog\Articles\Infrastructure\GetAllArticlesController::class, '__invoke']);
        Route::get('/articles/{articleId}', [Src\Blog\Articles\Infrastructure\GetArticleController::class, '__invoke']);
        Route::post('/articles/create-or-update', [Src\Blog\Articles\Infrastructure\PostCreateOrUpdateArticleController::class, '__invoke']);        

    //});

    

});

Route::get('/test-email', function (Request $request) {
    try {
        $email = $request->query('email', 'info@bagsandtea.com');
        $message = $request->query('message', 'This is a test email from Bags and Tea using SendGrid!');
        
        Mail::to($email)->send(new TestEmail($message));
        
        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully!',
            'sent_to' => $email,
            'email_message' => $message
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send email: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/test-order-confirmation', function (Request $request) {
    try {
        $email = $request->query('email', 'nicolas.tabares.tech@gmail.com');
        $orderNumber = $request->query('order', 'BT-2025-000006');
        $locale = $request->query('locale'); // Optional locale override
        
        Mail::to($email)->send(new OrderConfirmation($orderNumber, $locale));
        
        return response()->json([
            'success' => true,
            'message' => 'Order confirmation email sent successfully!',
            'sent_to' => $email,
            'order_number' => $orderNumber,
            'locale' => $locale ?: 'auto-detected'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send order confirmation: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::get('/test-new-order-event', function (Request $request) {
    try {
        $orderNumber = $request->query('order', 'BT-2025-000006');

        // Dispatch the NewOrderCreated event
        NewOrderCreated::dispatch($orderNumber);

        return response()->json([
            'success' => true,
            'message' => 'NewOrderCreated event dispatched successfully!',
            'order_number' => $orderNumber
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to dispatch NewOrderCreated event: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Horizon Test Routes
Route::get('/test-horizon', function (Request $request) {
    try {
        $message = 'Job dispatched at ' . now()->toDateTimeString();

        TestHorizonJob::dispatch($message);

        return response()->json([
            'success' => true,
            'message' => 'Test job dispatched to Redis queue!',
            'job_message' => $message,
            'horizon_url' => url('/horizon'),
            'instructions' => [
                '1. Check /horizon to see the job in the dashboard',
                '2. Start horizon with: docker compose exec app php artisan horizon',
                '3. Check logs for job execution'
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to dispatch job: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/test-horizon-batch/{count?}', function (Request $request, $count = 5) {
    try {
        for ($i = 1; $i <= $count; $i++) {
            $message = "Batch job #{$i} dispatched at " . now()->toDateTimeString();
            TestHorizonJob::dispatch($message);
        }

        return response()->json([
            'success' => true,
            'message' => "Dispatched {$count} test jobs to Redis queue!",
            'horizon_url' => url('/horizon')
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to dispatch batch jobs: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/horizon-status', function () {
    try {
        \Illuminate\Support\Facades\Redis::ping();
        $redisConnected = true;
    } catch (\Exception $e) {
        $redisConnected = false;
    }

    return response()->json([
        'redis_connected' => $redisConnected,
        'horizon_url' => url('/horizon'),
        'queue_connection' => config('queue.default'),
        'redis_host' => config('database.redis.default.host'),
        'redis_port' => config('database.redis.default.port'),
    ]);
});

// Vinted Scraping Test Routes
Route::get('/vinted/test-scrape', function (Request $request) {
    try {
        $searchQuery = \Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel::where('is_active', true)->first();

        if (!$searchQuery) {
            return response()->json([
                'success' => false,
                'message' => 'No active search queries found. Run the seeder first.'
            ], 404);
        }

        $fetchWebpage = new \Src\ThirdPartyServices\Firecrawl\Application\Fetch\FetchWebpageContent();
        $scraper = new \Src\ThirdPartyServices\Vinted\Application\ScrapeVintedSearchResults($fetchWebpage);

        $startTime = microtime(true);
        $html = $scraper($searchQuery->vinted_search_url);
        $elapsed = round(microtime(true) - $startTime, 2);

        if ($html === null) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to scrape URL. Check logs for details.',
                'search_query' => [
                    'id' => $searchQuery->id,
                    'name' => $searchQuery->name,
                    'brand' => $searchQuery->brand,
                    'url' => $searchQuery->vinted_search_url,
                ]
            ], 500);
        }

        // Update last_scanned_at
        $searchQuery->update(['last_scanned_at' => now()]);

        // Return HTML directly so you can view it in browser
        return response($html, 200)
            ->header('Content-Type', 'text/html')
            ->header('X-Scrape-Time', $elapsed . 's')
            ->header('X-Content-Length', strlen($html))
            ->header('X-Search-Query', $searchQuery->name . ' - ' . $searchQuery->brand);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Scraping failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Test scrape + AI parsing
Route::get('/vinted/test-parse', function (Request $request) {
    try {
        $searchQuery = \Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel::where('is_active', true)->first();

        if (!$searchQuery) {
            return response()->json([
                'success' => false,
                'message' => 'No active search queries found.'
            ], 404);
        }

        // Step 1: Scrape
        $fetchWebpage = new \Src\ThirdPartyServices\Firecrawl\Application\Fetch\FetchWebpageContent();
        $scraper = new \Src\ThirdPartyServices\Vinted\Application\ScrapeVintedSearchResults($fetchWebpage);

        $html = $scraper($searchQuery->vinted_search_url);

        if ($html === null) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to scrape URL.',
                'search_query' => $searchQuery->name
            ], 500);
        }

        // Step 2: Parse with AI
        $openaiClient = \OpenAI::client(config('services.openai.api_key'));
        $parser = new \Src\ThirdPartyServices\Vinted\Application\ParseVintedSearchResults($openaiClient);

        $parsed = $parser($html, $searchQuery->vinted_search_url);

        // Step 3: Filter interesting deals (price <= ideal_price)
        $interestingDeals = array_filter($parsed['listings'] ?? [], function ($listing) use ($searchQuery) {
            return $listing['price'] !== null && $listing['price'] <= (float) $searchQuery->ideal_price;
        });

        return response()->json([
            'success' => true,
            'search_query' => [
                'name' => $searchQuery->name,
                'brand' => $searchQuery->brand,
                'ideal_price' => $searchQuery->ideal_price,
            ],
            'total_listings_found' => count($parsed['listings'] ?? []),
            'interesting_deals_count' => count($interestingDeals),
            'interesting_deals' => array_values($interestingDeals),
            'all_listings' => $parsed['listings'] ?? [],
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Parsing failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Full scan: scrape, parse, and save to database (with pagination)
Route::get('/vinted/scan', function (Request $request) {
    try {
        $searchQuery = \Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel::where('is_active', true)->first();

        if (!$searchQuery) {
            return response()->json([
                'success' => false,
                'message' => 'No active search queries found.'
            ], 404);
        }

        $fetchWebpage = new \Src\ThirdPartyServices\Firecrawl\Application\Fetch\FetchWebpageContent();
        $scraper = new \Src\ThirdPartyServices\Vinted\Application\ScrapeVintedSearchResults($fetchWebpage);
        $openaiClient = \OpenAI::client(config('services.openai.api_key'));
        $parser = new \Src\ThirdPartyServices\Vinted\Application\ParseVintedSearchResults($openaiClient);

        $savedCount = 0;
        $newInterestingCount = 0;
        $maxPages = $searchQuery->max_pages ?? 3;
        $pagesScraped = 0;

        // Iterate through pages
        for ($page = 1; $page <= $maxPages; $page++) {
            $pageUrl = $searchQuery->getUrlForPage($page);

            \Illuminate\Support\Facades\Log::info("Scraping page {$page}/{$maxPages}", ['url' => $pageUrl]);

            // Scrape this page
            $html = $scraper($pageUrl);

            if ($html === null) {
                \Illuminate\Support\Facades\Log::warning("Failed to scrape page {$page}, stopping pagination");
                break;
            }

            $pagesScraped++;

            // Parse with AI
            $parsed = $parser($html, $pageUrl);
            $listings = $parsed['listings'] ?? [];

            // If no listings found, we've reached the end
            if (empty($listings)) {
                \Illuminate\Support\Facades\Log::info("No listings found on page {$page}, stopping pagination");
                break;
            }

            // Save listings to database
            foreach ($listings as $listing) {
                if (empty($listing['vinted_item_id'])) {
                    continue;
                }

                $isInteresting = $listing['price'] !== null && $listing['price'] <= (float) $searchQuery->ideal_price;
                $existingListing = \Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel::where('vinted_item_id', $listing['vinted_item_id'])->first();

                \Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel::updateOrCreate(
                    ['vinted_item_id' => $listing['vinted_item_id']],
                    [
                        'bag_search_query_id' => $searchQuery->id,
                        'title' => $listing['title'],
                        'price' => $listing['price'],
                        'currency' => $listing['currency'] ?? 'EUR',
                        'url' => $listing['url'],
                        'main_image_url' => $listing['main_image_url'],
                        'brand_detected' => $listing['brand'],
                        'size' => $listing['size'],
                        'is_interesting' => $isInteresting,
                        'raw_data' => $listing,
                        'scraped_at' => now(),
                    ]
                );

                $savedCount++;

                if ($isInteresting && !$existingListing) {
                    $newInterestingCount++;
                }
            }
        }

        // Update last_scanned_at
        $searchQuery->update(['last_scanned_at' => now()]);

        return response()->json([
            'success' => true,
            'search_query' => $searchQuery->name,
            'pages_scraped' => $pagesScraped,
            'max_pages' => $maxPages,
            'total_listings_processed' => $savedCount,
            'new_interesting_deals' => $newInterestingCount,
            'message' => $newInterestingCount > 0
                ? "{$newInterestingCount} new deals found under {$searchQuery->ideal_price}€!"
                : 'No new interesting deals found.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Scan failed: ' . $e->getMessage()
        ], 500);
    }
});

// Get saved interesting deals
Route::get('/vinted/deals', function () {
    $deals = \Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel::where('is_interesting', true)
        ->orderBy('price', 'asc')
        ->get();

    return response()->json([
        'success' => true,
        'count' => $deals->count(),
        'deals' => $deals->map(fn($d) => [
            'id' => $d->id,
            'title' => $d->title,
            'price' => $d->price,
            'currency' => $d->currency,
            'url' => $d->url,
            'image' => $d->main_image_url,
            'brand' => $d->brand_detected,
            'notification_sent' => $d->notification_sent,
            'scraped_at' => $d->scraped_at,
        ])
    ]);
});

// List all search queries
Route::get('/vinted/search-queries', function () {
    $queries = \Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel::all();

    return response()->json([
        'success' => true,
        'data' => $queries->map(fn($q) => [
            'id' => $q->id,
            'name' => $q->name,
            'brand' => $q->brand,
            'ideal_price' => $q->ideal_price,
            'max_price' => $q->max_price,
            'is_active' => $q->is_active,
            'last_scanned_at' => $q->last_scanned_at,
            'test_url' => url("/api/vinted/test-scrape/{$q->id}"),
        ])
    ]);
});
*/