<?php

namespace Src\Order\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'payment_intent_id',
        'currency',
        'customer_email',
        'customer_name',
        'customer_phone',
        'billing_address',
        'shipping_address',
        'subtotal',
        'total_discounts',
        'total_fees',
        'shipping_amount',
        'shipping_promotion_id',
        'tax_amount',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'subtotal' => 'decimal:2',
        'total_discounts' => 'decimal:2',
        'total_fees' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function suborders(): HasMany
    {
        return $this->hasMany(SuborderEloquentModel::class, 'order_id');
    }

    public function orderFees(): HasMany
    {
        return $this->hasMany(OrderFeeEloquentModel::class, 'order_id');
    }
}