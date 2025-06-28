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
        ]);

        $a = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'As good as new',
                'es' => 'Como nuevo'
            ],
            'code' => "A",
        ]);

        $ab = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'Very good condition',
                'es' => 'Muy buen estado'
            ],
            'code' => "AB",
        ]);

        $b = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'Good condition',
                'es' => 'Buen estado'
            ],
            'code' => "B",
        ]);

        $c = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'Used',
                'es' => 'Usado'
            ],
            'code' => "C",
        ]);

        $d = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => 'Very used',
                'es' => 'Muy usado'
            ],
            'code' => "D",
        ]);

    }
}
