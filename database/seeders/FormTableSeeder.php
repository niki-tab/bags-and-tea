<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Crm\Forms\Domain\FormModel;


class FormTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        FormModel::factory()->count(20)->create();

        FormModel::create([
            'form_name' => 'Sell your bag',
            'form_identifier' => 'sell-your-bag',
            'form_description' => '',
            'form_fields' => [
                [
                    'label' => 'pages/we-buy-your-bag.form-name',
                    'placeholder' => 'pages/we-buy-your-bag.form-name',
                    'type' => 'text',
                    'name' => 'name',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-last-name',
                    'placeholder' => 'pages/we-buy-your-bag.form-last-name',
                    'type' => 'text',
                    'name' => 'last_name',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-email',
                    'placeholder' => 'pages/we-buy-your-bag.form-email',
                    'type' => 'email',
                    'name' => 'email',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-phone',
                    'placeholder' => 'pages/we-buy-your-bag.form-phone',
                    'type' => 'tel',
                    'name' => 'phone',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-city',
                    'placeholder' => 'pages/we-buy-your-bag.form-city',
                    'type' => 'text',
                    'name' => 'city',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-brand',
                    'placeholder' => 'pages/we-buy-your-bag.form-brand',
                    'type' => 'text',
                    'name' => 'brand',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-complements',
                    'type' => 'radio',
                    'name' => 'complements',
                    'required' => true,
                    'options' => [
                        'option-1' => 'pages/we-buy-your-bag.form-complements-option-1',
                        'option-2' => 'pages/we-buy-your-bag.form-complements-option-2',
                    ],
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-documentation',
                    'type' => 'radio',
                    'name' => 'documentation',
                    'required' => true,
                    'options' => [
                        'option-1' => 'pages/we-buy-your-bag.form-documentation-option-1',
                        'option-2' => 'pages/we-buy-your-bag.form-documentation-option-2',
                    ],
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-message',    
                    'placeholder' => 'pages/we-buy-your-bag.form-message',
                    'type' => 'textarea',
                    'name' => 'message',
                    'required' => false,
                ],
            ],
            'is_active' => true,
        ]);

    }
}
