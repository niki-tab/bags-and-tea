<?php

namespace Database\Factories;

use Ramsey\Uuid\Uuid;

use Src\Blog\Articles\Model\ArticleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FormFactory extends Factory
{   
    protected $model = \Src\Crm\Forms\Domain\FormModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "form_name" => fake()->sentence(),
            "form_identifier" => fake()->slug(),
            "form_description" => fake()->sentence(),
            "form_fields" => fake()->randomElement([
                [
                    "label" => "Name",
                    "name" => "name",
                    "type" => "text",
                    "placeholder" => "Enter your name"
                ],
                [
                    "label" => "Email",
                    "name" => "email",
                    "type" => "email",
                    "placeholder" => "Enter your email"
                ],
                [
                    "label" => "Phone",
                    "name" => "phone",
                    "type" => "tel",
                    "placeholder" => "Enter your phone"
                ],
                [
                    "label" => "Message",
                    "name" => "message",
                    "type" => "textarea",
                    "placeholder" => "Enter your message"
                ],
                [
                    'label' => 'Documentation',
                    'type' => 'checkbox',
                    'name' => 'documentation',
                    'options' => [
                        'option-1' => 'Option 1',
                        'option-2' => 'Option 2',
                    ],
                ]
                
                
            ]),
            "is_active" => fake()->boolean()
        ];

    }
}
