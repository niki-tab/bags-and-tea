<?php

namespace Src\Vendors\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Order\Infrastructure\Eloquent\SuborderEloquentModel;

class VendorCommissionEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'vendor_commissions';

    protected $fillable = [
        'suborder_id',
        'vendor_id',
        'order_amount',
        'commission_rate',
        'commission_amount',
        'vendor_payout',
        'payout_status',
        'payout_date',
        'payout_reference',
        'payout_notes',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_payout' => 'decimal:2',
        'payout_date' => 'datetime',
    ];

    public function suborder(): BelongsTo
    {
        return $this->belongsTo(SuborderEloquentModel::class, 'suborder_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(VendorEloquentModel::class, 'vendor_id');
    }
}