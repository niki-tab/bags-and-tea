<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Livewire\Admin\Products\ProductForm;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;
use Src\Products\Brands\Infrastructure\Eloquent\BrandEloquentModel;
use Livewire\Livewire;

class ProductFormSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_unique_slug_when_duplicate_exists()
    {
        // Create a brand first
        $brand = BrandEloquentModel::create([
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'Test Brand',
            'slug' => 'test-brand'
        ]);

        // Create a product with a specific slug to test against
        ProductEloquentModel::create([
            'id' => '123e4567-e89b-12d3-a456-426614174001',
            'name' => ['en' => 'Test Product', 'es' => 'Producto de Prueba'],
            'slug' => ['en' => 'test-product', 'es' => 'producto-de-prueba'],
            'brand_id' => $brand->id,
            'price' => 10.00,
            'stock' => 1,
            'product_type' => 'simple',
            'status' => 'approved',
        ]);

        // Test the ProductForm component
        $component = Livewire::test(ProductForm::class)
            ->set('name_en', 'Test Product')
            ->call('updatedNameEn');

        // The slug should be unique (test-product-1, not test-product)
        $this->assertNotEquals('test-product', $component->get('slug_en'));
        $this->assertEquals('test-product-1', $component->get('slug_en'));
    }

    public function test_generates_regular_slug_when_no_duplicate_exists()
    {
        // Test the ProductForm component with a unique name
        $component = Livewire::test(ProductForm::class)
            ->set('name_en', 'Unique Product Name')
            ->call('updatedNameEn');

        // The slug should be the base slug since no duplicate exists
        $this->assertEquals('unique-product-name', $component->get('slug_en'));
    }
}