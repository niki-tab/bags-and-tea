<?php

namespace Src\Order\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Vendors\Infrastructure\VendorEloquentModel;
use Src\Vendors\Infrastructure\Eloquent\VendorCommissionEloquentModel;

class SuborderEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'suborders';

    protected $fillable = [
        'order_id',
        'vendor_id',
        'suborder_number',
        'status',
        'subtotal',
        'vendor_commission_rate',
        'commission_amount',
        'vendor_payout',
        'tracking_number',
        'shipping_carrier',
        'shipped_at',
        'delivered_at',
        'vendor_notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'vendor_commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_payout' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderEloquentModel::class, 'order_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(VendorEloquentModel::class, 'vendor_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItemEloquentModel::class, 'suborder_id');
    }

    public function vendorCommission(): HasMany
    {
        return $this->hasMany(VendorCommissionEloquentModel::class, 'suborder_id');
    }
}