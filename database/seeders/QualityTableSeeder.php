<?php

namespace Database\Seeders;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Src\Blog\Articles\Model\ArticleModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Src\Products\Quality\Infrastructure\Eloquent\QualityEloquentModel;

class QualityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   

        $aPlus = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'New',
                'es' => 'Nuevo'
            ],
            'code' => "A+",
            'display_order' => 1,
        ]);

        $a = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'As good as new',
                'es' => 'Como nuevo'
            ],
            'code' => "A",
            'display_order' => 2,
        ]);

        $ab = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'Very good condition',
                'es' => 'Muy buen estado'
            ],
            'code' => "AB",
            'display_order' => 3,
        ]);

        $b = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'Good condition',
                'es' => 'Buen estado'
            ],
            'code' => "B",
            'display_order' => 4,
        ]);

        $c = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'Used',
                'es' => 'Usado'
            ],
            'code' => "C",
            'display_order' => 5,
        ]);

        $d = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'Very used',
                'es' => 'Muy usado'
            ],
            'code' => "D",
            'display_order' => 6,
        ]);

    }
}
