<?php

namespace Src\Blog\Categories\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Src\Blog\Articles\Model\ArticleModel;
use Src\Shared\Infrastructure\Eloquent\BelongsToSite;

class BlogCategoryEloquentModel extends Model
{
    use HasUuids, HasTranslations, SoftDeletes, BelongsToSite;

    protected $table = 'blog_categories';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'description_1',
        'description_2',
        'parent_id',
        'display_order',
        'is_active',
        'color',
    ];

    protected $casts = [
        'id' => 'string',
        'parent_id' => 'string',
        'name' => 'string',
        'slug' => 'string',
        'description_1' => 'string',
        'description_2' => 'string',
        'display_order' => 'integer',
        'is_active' => 'boolean',
        'color' => 'string',
    ];

    public $translatable = ['name', 'slug', 'description_1', 'description_2'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogCategoryEloquentModel::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(BlogCategoryEloquentModel::class, 'parent_id');
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(
            ArticleModel::class,
            'blog_article_category',
            'blog_category_id',
            'blog_article_id'
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