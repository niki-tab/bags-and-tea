<?php

namespace Src\Order\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class OrderItemEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'order_items';

    protected $fillable = [
        'suborder_id',
        'product_id',
        'product_name',
        'product_sku',
        'unit_price',
        'quantity',
        'total_price',
        'product_snapshot',
    ];

    protected $casts = [
        'product_name' => 'array',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'product_snapshot' => 'array',
    ];

    public function suborder(): BelongsTo
    {
        return $this->belongsTo(SuborderEloquentModel::class, 'suborder_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductEloquentModel::class, 'product_id');
    }
}