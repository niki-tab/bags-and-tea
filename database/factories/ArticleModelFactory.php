<?php

namespace Database\Factories;

use Ramsey\Uuid\Uuid;

use Src\Blog\Articles\Model\ArticleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'main_image' => fake()->imageUrl(),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->sentence(),
            'meta_keywords' => fake()->sentence(),
            'is_visible' => fake()->boolean(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ArticleModel $article) {
            $article->setTranslation('title', 'en', $this->faker->word);
            $article->setTranslation('title', 'es', $this->faker->word);
            $article->setTranslation('slug', 'en', $this->faker->slug);
            $article->setTranslation('slug', 'es', $this->faker->slug);
            $article->setTranslation('body', 'en', $this->faker->paragraph);
            $article->setTranslation('body', 'es', $this->faker->paragraph);
            $article->setTranslation('meta_title', 'en', $this->faker->sentence);
            $article->setTranslation('meta_title', 'es', $this->faker->sentence);
            $article->setTranslation('meta_description', 'en', $this->faker->sentence);
            $article->setTranslation('meta_description', 'es', $this->faker->sentence);
            $article->save(); // Save after setting translations
        });
    }
}
