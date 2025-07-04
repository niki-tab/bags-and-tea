<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Auth\User\Infrastructure\Eloquent\UserEloquentModel;
use App\Auth\Role\Infrastructure\Eloquent\RoleEloquentModel;
use Src\Vendors\Infrastructure\Eloquent\VendorEloquentModel;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get vendor role
        $vendorRole = RoleEloquentModel::where('name', 'vendor')->first();
        
        if (!$vendorRole) {
            $this->command->error('Vendor role not found. Please run RoleSeeder first.');
            return;
        }

        // Create vendor users
        $vendors = [
            [
                'name' => 'Fashion Boutique Spain',
                'email' => 'vendor1@bagsandtea.com',
                'business_name' => 'Fashion Boutique Spain S.L.',
                'tax_id' => 'ESB12345678',
                'phone' => '+34 123 456 789',
                'website' => 'https://fashionboutique.es',
                'description' => 'Premium fashion accessories and luxury handbags from Spain.',
                'billing_address' => [
                    'street' => 'Calle Gran Vía 123',
                    'city' => 'Madrid',
                    'state' => 'Madrid',
                    'postal_code' => '28013',
                    'country' => 'Spain'
                ],
                'shipping_address' => [
                    'street' => 'Calle Gran Vía 123',
                    'city' => 'Madrid',
                    'state' => 'Madrid',
                    'postal_code' => '28013',
                    'country' => 'Spain'
                ]
            ],
            [
                'name' => 'Elite Leather Goods',
                'email' => 'vendor2@bagsandtea.com',
                'business_name' => 'Elite Leather Goods Ltd.',
                'tax_id' => 'ESB87654321',
                'phone' => '+34 987 654 321',
                'website' => 'https://eliteleather.com',
                'description' => 'Handcrafted leather bags and accessories.',
                'billing_address' => [
                    'street' => 'Passeig de Gràcia 456',
                    'city' => 'Barcelona',
                    'state' => 'Catalonia',
                    'postal_code' => '08007',
                    'country' => 'Spain'
                ],
                'shipping_address' => [
                    'street' => 'Passeig de Gràcia 456',
                    'city' => 'Barcelona',
                    'state' => 'Catalonia',
                    'postal_code' => '08007',
                    'country' => 'Spain'
                ]
            ]
        ];

        foreach ($vendors as $vendorData) {
            // Create user
            $user = UserEloquentModel::create([
                'id' => Str::uuid(),
                'name' => $vendorData['name'],
                'email' => $vendorData['email'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);

            // Assign vendor role
            $user->roles()->attach($vendorRole->id);

            // Create vendor profile
            $vendor = VendorEloquentModel::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'business_name' => $vendorData['business_name'],
                'tax_id' => $vendorData['tax_id'],
                'phone' => $vendorData['phone'],
                'website' => $vendorData['website'],
                'description' => $vendorData['description'],
                'billing_address' => $vendorData['billing_address'],
                'shipping_address' => $vendorData['shipping_address'],
                'status' => 'active',
                'commission_rate' => 15.00,
            ]);

            $this->command->info("Created vendor: {$vendor->business_name} (User: {$user->email})");
        }

        // Assign existing products to vendors randomly
        $vendors = VendorEloquentModel::all();
        $products = ProductEloquentModel::whereNull('vendor_id')->get();

        if ($vendors->count() > 0 && $products->count() > 0) {
            foreach ($products as $product) {
                $randomVendor = $vendors->random();
                $product->update(['vendor_id' => $randomVendor->id]);
            }
            
            $this->command->info("Assigned {$products->count()} products to vendors.");
        }
    }
}
