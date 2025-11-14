<?php

namespace Src\Products\Product\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Src\Products\Brands\Infrastructure\Eloquent\BrandEloquentModel;
use Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel;
use Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel;
use Src\Products\Quality\Infrastructure\Eloquent\QualityEloquentModel;
use Src\Vendors\Infrastructure\Eloquent\VendorEloquentModel;

class ProductEloquentModel extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'products';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'name',
        'brand_id',
        'vendor_id',
        'slug',
        'status',
        'sku',
        'description_1',
        'description_2',
        'height',
        'width',
        'depth',
        'weight',
        'origin_country',
        'quality_id',
        'product_type',
        'price',
        'discounted_price',
        'deal_price',
        'sell_mode',
        'sell_mode_quantity',
        'stock',
        'stock_unit',
        'out_of_stock',
        'is_sold_out',
        'is_hidden',
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
        'vendor_id' => 'string',
        'slug' => 'string',
        'status' => 'string',
        'sku' => 'string',
        'description_1' => 'string',
        'description_2' => 'string',
        'height' => 'float',
        'width' => 'float',
        'depth' => 'float',
        'weight' => 'float',
        'origin_country' => 'string',
        'quality_id' => 'string',
        'product_type' => 'string',
        'price' => 'float',
        'discounted_price' => 'float',
        'deal_price' => 'float',
        'sell_mode' => 'string',
        'sell_mode_quantity' => 'string',
        'stock' => 'integer',
        'stock_unit' => 'string',
        'out_of_stock' => 'boolean',
        'is_sold_out' => 'boolean',
        'is_hidden' => 'boolean',
        'featured' => 'boolean',
        'featured_position' => 'integer',
        'meta_title' => 'string',
        'meta_description' => 'string',
    ];

    public $translatable = ['name', 'description_1', 'description_2', 'slug', 'meta_title', 'meta_description'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(BrandEloquentModel::class, 'brand_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(VendorEloquentModel::class, 'vendor_id');
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
        )->withTimestamps()
        ->withPivot('id')
        ->using(\Src\Products\Product\Infrastructure\Eloquent\ProductAttributePivot::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true)->orderBy('featured_position');
    }

    public function scopeInStock($query)
    {
        return $query->where('out_of_stock', false);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMediaModel::class, 'product_id')->ordered();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductMediaModel::class, 'product_id')->images()->ordered();
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ProductMediaModel::class, 'product_id')->videos()->ordered();
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductMediaModel::class, 'product_id')->images()->primary();
    }
}
