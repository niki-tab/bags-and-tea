<?php

namespace Src\Sites\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;
use Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel;
use Src\Products\Brands\Infrastructure\Eloquent\BrandEloquentModel;
use Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel;
use Src\Order\Infrastructure\Eloquent\OrderEloquentModel;
use Src\Cart\Infrastructure\Eloquent\CartEloquentModel;
use Src\Blog\Articles\Infrastructure\Eloquent\BlogArticleEloquentModel;
use Src\Blog\Categories\Infrastructure\Eloquent\BlogCategoryEloquentModel;

class SiteEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'sites';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'slug' => 'string',
        'domain' => 'string',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(ProductEloquentModel::class, 'site_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(CategoryEloquentModel::class, 'site_id');
    }

    public function brands(): HasMany
    {
        return $this->hasMany(BrandEloquentModel::class, 'site_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(AttributeEloquentModel::class, 'site_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderEloquentModel::class, 'site_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(CartEloquentModel::class, 'site_id');
    }

    public function blogArticles(): HasMany
    {
        return $this->hasMany(BlogArticleEloquentModel::class, 'site_id');
    }

    public function blogCategories(): HasMany
    {
        return $this->hasMany(BlogCategoryEloquentModel::class, 'site_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
    }

    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}
