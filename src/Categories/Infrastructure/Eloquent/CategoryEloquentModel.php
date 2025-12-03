<?php

namespace Src\Categories\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;
use Src\Shared\Infrastructure\Eloquent\BelongsToSite;

class CategoryEloquentModel extends Model
{
    use HasUuids, HasTranslations, BelongsToSite;

    protected $table = 'categories';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'description_1',
        'description_2',
        'meta_title',
        'meta_description',
        'parent_id',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'id' => 'string',
        'parent_id' => 'string',
        'name' => 'string',
        'slug' => 'string',
        'description_1' => 'string',
        'description_2' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public $translatable = ['name', 'slug', 'description_1', 'description_2', 'meta_title', 'meta_description'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CategoryEloquentModel::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CategoryEloquentModel::class, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductEloquentModel::class,
            'product_category',
            'category_id',
            'product_id'
        )->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithChildren($query)
    {
        return $query->with('children');
    }
}