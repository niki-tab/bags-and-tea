<?php

namespace Src\Order\Shipping\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Src\Vendors\Infrastructure\Eloquent\VendorEloquentModel;

class ShippingRateEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'shipping_rates';

    protected $fillable = [
        'vendor_id',
        'country_code',
        'zone_name',
        'rate_name',
        'rate_type',
        'base_rate',
        'per_kg_rate',
        'free_shipping_threshold',
        'delivery_days_min',
        'delivery_days_max',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'per_kg_rate' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'delivery_days_min' => 'integer',
        'delivery_days_max' => 'integer',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(VendorEloquentModel::class, 'vendor_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCountry($query, string $countryCode)
    {
        return $query->where('country_code', $countryCode)
                    ->orWhere('country_code', '*');
    }

    public function scopeOrderByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }
}