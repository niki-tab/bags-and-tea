<?php

namespace Src\Products\Product\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;
use Src\Products\Brands\Infrastructure\Eloquent\BrandEloquentModel;
use Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel;
use Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel;
use Src\Products\Quality\Infrastructure\Eloquent\QualityEloquentModel;
class ProductEloquentModel extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'products';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'brand_id',
        'slug',
        'description_1',
        'description_2',
        'origin',
        'quality_id',
        'product_type',
        'price',
        'discounted_price',
        'sell_unit',
        'sell_mode',
        'stock',
        'stock_unit',
        'out_of_stock',
        'image',
        'featured',
        'featured_position',
        'meta_title',
        'meta_description',
    ];


    // Define the cast attributes
    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'brand_id' => 'string',
        'slug' => 'string',
        'description_1' => 'string',
        'description_2' => 'string',
        'origin' => 'string',
        'quality_id' => 'string',
        'product_type' => 'string',
        'price' => 'float',
        'discounted_price' => 'float',
        'sell_unit' => 'string',
        'sell_mode' => 'string',
        'stock' => 'string',
        'stock_unit' => 'string',
        'out_of_stock' => 'boolean',
        'image' => 'string',
        'featured' => 'boolean',
        'featured_position' => 'integer',
        'meta_title' => 'string',
        'meta_description' => 'string',
    ];

    public $translatable = ['name', 'description_1', 'description_2', 'origin', 'slug', 'meta_title', 'meta_description'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(BrandEloquentModel::class, 'brand_id');
    }

    public function quality(): BelongsTo
    {
        return $this->belongsTo(QualityEloquentModel::class, 'quality_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            CategoryEloquentModel::class,
            'product_category',
            'product_id',
            'category_id'
        )->withTimestamps()
         ->withPivot('id')
         ->using(\Src\Products\Product\Infrastructure\Eloquent\ProductCategoryPivot::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeEloquentModel::class,
            'product_attribute',
            'product_id',
            'attribute_id'
        )->withTimestamps();
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true)->orderBy('featured_position');
    }

    public function scopeInStock($query)
    {
        return $query->where('out_of_stock', false);
    }
}
