<?php

namespace Src\Products\Product\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;
class ProductEloquentModel extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'products';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'brand',
        'slug',
        'description_1',
        'description_2',
        'origin',
        'quality',
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
        'brand' => 'string',
        'slug' => 'string',
        'description_1' => 'string',
        'description_2' => 'string',
        'origin' => 'string',
        'quality' => 'string',
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

    public $translatable = ['name', 'brand', 'description_1', 'description_2', 'origin', 'slug', 'meta_title', 'meta_description', 'food_type'];
}
