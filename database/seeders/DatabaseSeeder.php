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
      VendorSeeder::class,
      ArticleTableSeeder::class,
      QualityTableSeeder::class,
      
      // Categories and Shop Configuration
      CategoryTableSeeder::class,
      AttributeTableSeeder::class,
      ShopFilterTableSeeder::class,
      
      // Existing Seeders
      BrandTableSeeder::class,
      BagsCategorySeeder::class,
      FakeEnvironmentSeeder::class,
      UserTableSeeder::class,
      FormTableSeeder::class,
      FormSubmissionTableSeeder::class,
      
      // Marketplace Configuration
      MarketplaceFeeSeeder::class,
      ShippingRateSeeder::class,
      
    ]);

    }
}
