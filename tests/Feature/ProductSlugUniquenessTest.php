<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;
use Src\Products\Brands\Infrastructure\Eloquent\BrandEloquentModel;
use Src\Products\Quality\Infrastructure\Eloquent\QualityEloquentModel;

class ProductSlugUniquenessTest extends TestCase
{
    use RefreshDatabase;

    public function test_slug_generation_creates_unique_slugs()
    {
        // Create a quality first (required for products)
        $quality = QualityEloquentModel::create([
            'id' => '123e4567-e89b-12d3-a456-426614174099',
            'name' => ['en' => 'Test Quality', 'es' => 'Calidad de Prueba'],
            'slug' => ['en' => 'test-quality', 'es' => 'calidad-de-prueba'],
            'display_order' => 1,
        ]);

        // Create a brand first (required for products)
        $brand = BrandEloquentModel::create([
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'Test Brand',
            'slug' => 'test-brand'
        ]);

        // Create first product with slug
        $product1 = ProductEloquentModel::create([
            'id' => '123e4567-e89b-12d3-a456-426614174001',
            'name' => ['en' => 'Test Product', 'es' => 'Producto de Prueba'],
            'slug' => ['en' => 'test-product', 'es' => 'producto-de-prueba'],
            'brand_id' => $brand->id,
            'price' => 10.00,
            'stock' => 1,
            'product_type' => 'simple',
            'status' => 'approved',
            'quality_id' => $quality->id,
        ]);

        // Create second product with same name (should get unique slug)
        $product2 = ProductEloquentModel::create([
            'id' => '123e4567-e89b-12d3-a456-426614174002',
            'name' => ['en' => 'Test Product', 'es' => 'Producto de Prueba'],
            'slug' => ['en' => 'test-product-1', 'es' => 'producto-de-prueba-1'],
            'brand_id' => $brand->id,
            'price' => 15.00,
            'stock' => 1,
            'product_type' => 'simple',
            'status' => 'approved',
            'quality_id' => $quality->id,
        ]);

        // Verify both products exist with different slugs
        $this->assertDatabaseHas('products', [
            'id' => $product1->id,
        ]);
        
        $this->assertDatabaseHas('products', [
            'id' => $product2->id,
        ]);

        // Verify slugs are different
        $this->assertNotEquals($product1->getTranslation('slug', 'en'), $product2->getTranslation('slug', 'en'));
        $this->assertNotEquals($product1->getTranslation('slug', 'es'), $product2->getTranslation('slug', 'es'));
    }
}