<?php

namespace Tests\Feature\Products\Shop\Frontend;

use Database\Seeders\CategoryTableSeeder;
use Database\Seeders\ShopFilterTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Src\Products\Shop\Frontend\Shop;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the necessary data for the test
        $this->seed(CategoryTableSeeder::class);
        $this->seed(ShopFilterTableSeeder::class);
    }

    /** @test */
    public function query_parameters_are_translated_based_on_locale()
    {
        // Set locale to Spanish
        app()->setLocale('es');

        // Make a GET request to the shop page with translated query parameters
        $response = $this->get('/es/tienda?bolsos=bottega-veneta-bolsos&tipo-de-bolso=bolso-de-mano');

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the Livewire component has the correct selected filters
        $response->assertSeeLivewire('shop');
        $response->assertSee('Bolsos Bottega Veneta');
        $response->assertSee('Bolso de Mano');

        // Assert that the response contains the updateUrl event
        $response->assertSee('updateUrl');
    }
}