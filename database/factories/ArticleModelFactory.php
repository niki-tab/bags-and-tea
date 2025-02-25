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
            'main_image' => fake()->randomElement(['https://storage.bagsandtea.com/blog/WhatsApp%20Image%202025-02-18%20at%2017.00.54.jpeg', 'https://storage.bagsandtea.com/blog/IMG_20220624_192743.jpg', 'https://storage.bagsandtea.com/blog/00pp-bolso-bandolera-saint-laurent-loulou-modelo-grande-en-cuero-acolchado-con-motivos-de-espigas-negro.jpg']),
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
