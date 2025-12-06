<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VintedScanCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $queriesProcessed,
        public int $totalListings,
        public int $newInterestingDeals,
        public array $errors = []
    ) {
    }
}
