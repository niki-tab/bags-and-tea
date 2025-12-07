<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterestingDealsFound;
use Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel;

/**
 * Phase 3: Send email with verified listings
 *
 * This job checks for verified products that haven't been notified yet
 * and sends an email notification. Can be scheduled to run every 10 minutes.
 */
class SendVerifiedListingsEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 60;
    public int $tries = 3;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Log::info('Phase 3: Checking for verified listings to notify');

        // Get verified listings that:
        // - Are marked as interesting (is_interesting = true)
        // - AI verified as the actual product (is_verified_product = true)
        // - Haven't been notified yet (notification_sent = false)
        $verifiedListings = VintedListingEloquentModel::where('is_interesting', true)
            ->where('is_verified_product', true)
            ->where('notification_sent', false)
            ->orderBy('price', 'asc')
            ->get();

        if ($verifiedListings->isEmpty()) {
            Log::info('No verified listings to send');
            return;
        }

        Log::info('Sending email notification', ['listings_count' => $verifiedListings->count()]);

        Mail::to('nicolas.tabares.tech@gmail.com')
            ->send(new InterestingDealsFound($verifiedListings));

        // Mark as notified
        foreach ($verifiedListings as $listing) {
            $listing->update(['notification_sent' => true]);
        }

        Log::info('Email notification sent successfully', [
            'listings_count' => $verifiedListings->count(),
        ]);
    }
}
