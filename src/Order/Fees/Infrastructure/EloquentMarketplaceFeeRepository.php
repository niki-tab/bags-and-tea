<?php

namespace Src\Order\Fees\Infrastructure;

use Src\Order\Fees\Domain\MarketplaceFeeRepository;
use Src\Order\Fees\Infrastructure\Eloquent\MarketplaceFeeEloquentModel;

class EloquentMarketplaceFeeRepository implements MarketplaceFeeRepository
{
    public function findActiveVisibleFees(): array
    {
        $fees = MarketplaceFeeEloquentModel::where('is_active', true)
            ->where('visible_to_customer', true)
            ->where(function ($query) {
                $query->whereNull('effective_from')
                    ->orWhere('effective_from', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('effective_until')
                    ->orWhere('effective_until', '>=', now());
            })
            ->orderBy('display_order')
            ->get();

        return $fees->map(function ($fee) {
            return $fee->toArray();
        })->toArray();
    }

    public function findByCode(string $feeCode): ?array
    {
        $fee = MarketplaceFeeEloquentModel::where('fee_code', $feeCode)
            ->where('is_active', true)
            ->first();

        return $fee ? $fee->toArray() : null;
    }
}