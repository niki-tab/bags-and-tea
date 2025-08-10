<?php

namespace Src\Order\Fees\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MarketplaceFeeEloquentModel extends Model
{
    use HasUuids, HasTranslations, SoftDeletes;

    protected $table = 'marketplace_fees';

    public array $translatable = ['fee_name'];

    protected $fillable = [
        'fee_name',
        'fee_code',
        'description',
        'fee_type',
        'fixed_amount',
        'percentage_rate',
        'tiered_rates',
        'minimum_order_amount',
        'maximum_fee_amount',
        'applicable_countries',
        'applicable_payment_methods',
        'is_active',
        'effective_from',
        'effective_until',
        'visible_to_customer',
        'customer_label',
        'display_order',
    ];

    protected $casts = [
        'fixed_amount' => 'decimal:2',
        'percentage_rate' => 'decimal:4',
        'minimum_order_amount' => 'decimal:2',
        'maximum_fee_amount' => 'decimal:2',
        'tiered_rates' => 'json',
        'applicable_countries' => 'json',
        'applicable_payment_methods' => 'json',
        'is_active' => 'boolean',
        'visible_to_customer' => 'boolean',
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
        'display_order' => 'integer',
    ];
}