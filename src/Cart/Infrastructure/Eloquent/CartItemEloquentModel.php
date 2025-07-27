<?php

namespace Src\Cart\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class CartItemEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(CartEloquentModel::class, 'cart_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductEloquentModel::class, 'product_id');
    }

    public function scopeForCart($query, string $cartId)
    {
        return $query->where('cart_id', $cartId);
    }

    public function scopeForProduct($query, string $productId)
    {
        return $query->where('product_id', $productId);
    }
}