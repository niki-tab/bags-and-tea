<?php

namespace Src\Products\Brands\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class BrandEloquentModel extends Model
{
    use HasUuids, HasTranslations;

    protected $table = 'brands';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'description_1',
        'description_2',
        'logo_url',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'id' => 'string',
        'name' => 'array',
        'slug' => 'array',
        'description_1' => 'array',
        'description_2' => 'array',
        'logo_url' => 'string',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public $translatable = ['name', 'slug', 'description_1', 'description_2'];

    public function products(): HasMany
    {
        return $this->hasMany(ProductEloquentModel::class, 'brand_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}