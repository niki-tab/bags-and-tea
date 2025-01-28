<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ArticleModelFactory extends Factory
{   
    protected $model = \Src\Blog\Articles\Model\ArticleModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            'title' => fake()->sentence(),
            'slug' => fake()->slug(),
            'state' => fake()->randomElement(['draft', 'published', 'archived']),
            'body' => fake()->paragraph(),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->sentence(),
            'meta_keywords' => fake()->sentence(),
            'is_visible' => fake()->boolean(),
        ];
    }
}
