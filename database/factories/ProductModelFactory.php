<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductModel>
 */
class ProductModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->word,
            'description_1' => $this->faker->sentence,
            'product_type' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'image' => 'placeholder.jpg',
            'featured' => $this->faker->boolean,
        ];
    }
}
