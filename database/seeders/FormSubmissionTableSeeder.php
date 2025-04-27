<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Crm\Forms\Domain\FormModel;
use Src\Crm\Forms\Domain\FormSubmissionModel;


class FormSubmissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $forms = FormModel::all();

        foreach ($forms as $form) {
            FormSubmissionModel::factory()->create([
                'crm_form_id' => $form->id
            ]);
        }

    }
}
