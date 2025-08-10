<?php

namespace Src\Order\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderFeeEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'order_fees';

    protected $fillable = [
        'order_id',
        'marketplace_fee_id',
        'fee_code',
        'fee_name',
        'fee_type',
        'fee_rate',
        'applied_to_amount',
        'fee_amount',
        'visible_to_customer',
        'calculation_details',
    ];

    protected $casts = [
        'fee_rate' => 'decimal:4',
        'applied_to_amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'visible_to_customer' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderEloquentModel::class, 'order_id');
    }
}