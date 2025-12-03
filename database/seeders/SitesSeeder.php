<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Src\Sites\Infrastructure\Eloquent\SiteEloquentModel;

class SitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sites = [
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Bags & Tea',
                'slug' => 'bagsandtea',
                'domain' => 'bagsandtea.com',
                'is_active' => true,
                'settings' => [
                    'theme_color' => '#000000',
                    'contact_email' => 'info@bagsandtea.com',
                    'meta_title' => 'Bags & Tea - Luxury Bags',
                    'meta_description' => 'Premium luxury bags and accessories',
                ],
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Wallets & Tea',
                'slug' => 'walletsandtea',
                'domain' => 'walletsandtea.com',
                'is_active' => true,
                'settings' => [
                    'theme_color' => '#4A5568',
                    'contact_email' => 'info@walletsandtea.com',
                    'meta_title' => 'Wallets & Tea - Luxury Wallets',
                    'meta_description' => 'Premium luxury wallets and accessories',
                ],
            ],
        ];

        foreach ($sites as $siteData) {
            SiteEloquentModel::updateOrCreate(
                ['slug' => $siteData['slug']],
                $siteData
            );
        }

        $this->command->info('Sites seeded successfully!');
    }
}
