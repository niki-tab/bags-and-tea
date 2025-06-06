<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
    $this->call([
      // Auth & Role System
      RoleSeeder::class,
      AdminUserSeeder::class,
      ArticleTableSeeder::class,
      QualityTableSeeder::class,
      
      // Existing Seeders
      FakeEnvironmentSeeder::class,
      ArticleTableSeeder::class,
      RoleTableSeeder::class,
      UserTableSeeder::class,
      FormTableSeeder::class,
      FormSubmissionTableSeeder::class
      
    ]);

    }
}
