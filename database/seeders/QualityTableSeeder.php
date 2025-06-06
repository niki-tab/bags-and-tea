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
            'name' => 'New',
            'code' => "A+",
        ]);

        $a = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => 'As good as new',
            'code' => "A",
        ]);

        $ab = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => 'Very good condition',
            'code' => "AB",
        ]);

        $b = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => 'Good condition',
            'code' => "B",
        ]);

        $c = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => 'Used',
            'code' => "C",
        ]);

        $d = QualityEloquentModel::create([
            'id' => (string) Str::uuid(),
            'name' => 'Very used',
            'code' => "D",
        ]);

    }
}
