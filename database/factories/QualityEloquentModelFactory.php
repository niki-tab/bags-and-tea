<?php

namespace Database\Factories;

use Ramsey\Uuid\Uuid;

use Src\Blog\Articles\Model\ArticleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class QualityEloquentModelFactory extends Factory
{   
    protected $model = \Src\Products\Quality\Infrastructure\QualityEloquentModel::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Uuid::uuid4()->toString(),
        ];
    }

    
}
