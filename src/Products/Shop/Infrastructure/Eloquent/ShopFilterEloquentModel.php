<?php

namespace Src\Products\Shop\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

class ShopFilterEloquentModel extends Model
{
    use HasUuids, HasTranslations;

    protected $table = 'shop_filters';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'type',
        'reference_table',
        'product_column',
        'config',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'id' => 'string',
        'type' => 'string',
        'reference_table' => 'string',
        'product_column' => 'string',
        'config' => 'array',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public $translatable = ['name'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}