<?php

namespace Database\Seeders;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Seeder;
use Src\Blog\Articles\Model\ArticleModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        ArticleModel::factory()->count(20)->create();
    }
}
