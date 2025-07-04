<?php

namespace Src\Vendors\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Auth\User\Infrastructure\Eloquent\UserEloquentModel;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class VendorEloquentModel extends Model
{
    use HasFactory;

    protected $table = 'vendors';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'business_name',
        'tax_id',
        'billing_address',
        'shipping_address',
        'phone',
        'website',
        'description',
        'logo',
        'status',
        'commission_rate',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'business_name' => 'string',
        'tax_id' => 'string',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'phone' => 'string',
        'website' => 'string',
        'description' => 'string',
        'logo' => 'string',
        'status' => 'string',
        'commission_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserEloquentModel::class, 'user_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductEloquentModel::class, 'vendor_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function getFullAddressAttribute(): ?string
    {
        $address = $this->billing_address;
        if (!$address) {
            return null;
        }

        return sprintf(
            '%s, %s, %s %s, %s',
            $address['street'] ?? '',
            $address['city'] ?? '',
            $address['state'] ?? '',
            $address['postal_code'] ?? '',
            $address['country'] ?? ''
        );
    }
}