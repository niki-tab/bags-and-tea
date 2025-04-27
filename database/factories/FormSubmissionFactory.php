<?php

namespace Database\Factories;

use Ramsey\Uuid\Uuid;

use Src\Blog\Articles\Model\ArticleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FormSubmissionFactory extends Factory
{   
    protected $model = \Src\Crm\Forms\Domain\FormSubmissionModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "crm_form_answers" => [
                
                    "name" => "Nico",
                    "email" => "nico@gmail.com",
                    "phone" => "1234567890",
                    "message" => "Hello, how are you?",
                    "documentation" => true
                
            ]
        ];  
    }
}
