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
                [
                    'label' => 'pages/we-buy-your-bag.form-section-title',
                    'placeholder' =>'',
                    'image' => null,
                    'type' => 'section-title',
                    'name' => 'foto-section-title',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-file-1',
                    'placeholder' =>'',
                    'image' => '/images/forms/bag-image-1.svg',
                    'type' => 'file',
                    'name' => 'file-1',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-file-2',
                    'placeholder' =>'',
                    'image' => '/images/forms/bag-image-2.svg',
                    'type' => 'file',
                    'name' => 'file-2',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-file-3',
                    'placeholder' =>'',
                    'image' => '/images/forms/bag-image-3.svg',
                    'type' => 'file',
                    'name' => 'file-3',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-file-4',
                    'placeholder' =>'',
                    'image' => '/images/forms/bag-image-4.svg',
                    'type' => 'file',
                    'name' => 'file-4',
                    'required' => true,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-file-5',
                    'placeholder' =>'',
                    'image' => null,
                    'type' => 'file',
                    'name' => 'file-5',
                    'required' => false,
                ],
                [
                    'label' => 'pages/we-buy-your-bag.form-file-6',
                    'placeholder' =>'',
                    'image' => null,
                    'type' => 'file',
                    'name' => 'file-6',
                    'required' => false,
                ],  
                [
                    'label' => 'pages/we-buy-your-bag.form-file-7',
                    'placeholder' =>'',
                    'image' => null,
                    'type' => 'file',
                    'name' => 'file-7',
                    'required' => false,
                ],
            ],
            'is_active' => true,
        ]);

        FormModel::create([
            'form_name' => 'Contact us',
            'form_identifier' => 'contact-us',
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
                    'label' => 'pages/we-buy-your-bag.form-message',    
                    'placeholder' => 'pages/we-buy-your-bag.form-message',
                    'type' => 'textarea',
                    'name' => 'message',
                    'required' => true,
                ],
            ],
            'is_active' => true,
        ]);

        FormModel::create([
            'form_name' => 'Repair your bag',
            'form_identifier' => 'repair-your-bag',
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
                    'label' => 'pages/repair-your-bag.form-message',    
                    'placeholder' => 'pages/repair-your-bag.form-message',
                    'type' => 'textarea',
                    'name' => 'message',
                    'required' => true,
                ],
                [
                    'label' => 'pages/repair-your-bag.form-intro-1',
                    'placeholder' => '',
                    'image' => null,
                    'type' => 'section-paragraph',
                    'name' => 'intro-paragraph-1',
                    'required' => false,
                ],
                [
                    'label' => 'pages/repair-your-bag.form-intro-2',
                    'placeholder' => '',
                    'image' => null,
                    'type' => 'section-paragraph',
                    'name' => 'intro-paragraph-2',
                    'required' => false,
                ],
                [
                    'label' => 'pages/repair-your-bag.form-photos-title',
                    'placeholder' => '',
                    'image' => null,
                    'type' => 'section-title',
                    'name' => 'photos-section-title',
                    'required' => false,
                ],
                [
                    'label' => 'pages/repair-your-bag.form-photos-description',
                    'placeholder' => '',
                    'image' => null,
                    'type' => 'section-paragraph',
                    'name' => 'photos-section-description',
                    'required' => false,
                ],
                [
                    'label' => 'Foto 1',
                    'placeholder' => '',
                    'image' => '/images/icons/imgDefault.png',
                    'type' => 'file',
                    'name' => 'file-1',
                    'required' => true,
                ],
                [
                    'label' => 'Foto 2',
                    'placeholder' => '',
                    'image' => '/images/icons/imgDefault.png',
                    'type' => 'file',
                    'name' => 'file-2',
                    'required' => true,
                ],
                [
                    'label' => 'Foto 3',
                    'placeholder' => '',
                    'image' => '/images/icons/imgDefault.png',
                    'type' => 'file',
                    'name' => 'file-3',
                    'required' => true,
                ],
                [
                    'label' => 'Foto 4',
                    'placeholder' => '',
                    'image' => '/images/icons/imgDefault.png',
                    'type' => 'file',
                    'name' => 'file-4',
                    'required' => false,
                ],
                [
                    'label' => 'Foto 5',
                    'placeholder' => '',
                    'image' => '/images/icons/imgDefault.png',
                    'type' => 'file',
                    'name' => 'file-5',
                    'required' => false,
                ],
                [
                    'label' => 'Foto 6',
                    'placeholder' => '',
                    'image' => '/images/icons/imgDefault.png',
                    'type' => 'file',
                    'name' => 'file-6',
                    'required' => false,
                ],
            ],
            'is_active' => true,
        ]);

    }
}
