<?php

namespace Database\Seeders;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Seeder;
use Src\Blog\Articles\Model\ArticleModel;
use Src\Authorization\Roles\Domain\RoleModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        RoleModel::create([
            'id'           => 1,
            'name'           => 'admin',
            'display_name'           => 'Admin',
        ]);

        RoleModel::create([
            'id'           => 2,
            'name'           => 'buyer',
            'display_name'           => 'Buyer',
        ]);

        RoleModel::create([
            'id'           => 3,
            'name'           => 'vendor',
            'display_name'           => 'vendor',
        ]);
    }
}
