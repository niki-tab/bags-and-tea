<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        // All European countries (unique list)
        $europeanCountries = [
            'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR',
            'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL',
            'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'AD', 'AL', 'BA', 
            'BY', 'CH', 'FO', 'GB', 'GI', 'GL', 'IS', 'LI', 'MC', 'MD', 
            'ME', 'MK', 'NO', 'RS', 'RU', 'SM', 'UA', 'VA'
        ];

        $shippingRates = [];

        // Create shipping rates for European countries
        foreach ($europeanCountries as $countryCode) {
            $baseRate = ($countryCode === 'ES') ? 15.00 : 25.00; // Spain: €15, Others: €25
            $deliveryDays = ($countryCode === 'ES') ? [3, 7] : [7, 10]; // Faster delivery for Spain
            
            $shippingRates[] = [
                'id' => Uuid::uuid4()->toString(),
                'vendor_id' => null, // Applies to all vendors
                'country_code' => $countryCode,
                'zone_name' => 'Europe',
                'rate_name' => 'Standard European Shipping',
                'rate_type' => 'fixed',
                'base_rate' => $baseRate,
                'per_kg_rate' => 0.00,
                'free_shipping_threshold' => null,
                'delivery_days_min' => $deliveryDays[0],
                'delivery_days_max' => $deliveryDays[1],
                'is_active' => true,
                'priority' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Create a default rate for rest of world (€100)
        $shippingRates[] = [
            'id' => Uuid::uuid4()->toString(),
            'vendor_id' => null,
            'country_code' => '*', // Wildcard for all other countries
            'zone_name' => 'Worldwide',
            'rate_name' => 'International Shipping',
            'rate_type' => 'fixed',
            'base_rate' => 100.00,
            'per_kg_rate' => 0.00,
            'free_shipping_threshold' => null,
            'delivery_days_min' => 7,
            'delivery_days_max' => 21,
            'is_active' => true,
            'priority' => 99, // Lower priority, used as fallback
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insert shipping rates in batches
        $chunks = array_chunk($shippingRates, 50);
        foreach ($chunks as $chunk) {
            DB::table('shipping_rates')->insert($chunk);
        }

        $this->command->info('Shipping rates created successfully:');
        $this->command->info('- Spain (ES): €15.00');
        $this->command->info('- Other Europe (' . (count($europeanCountries) - 1) . ' countries): €25.00');
        $this->command->info('- Rest of World: €100.00');
        $this->command->info('- Total rates created: ' . count($shippingRates));
    }
}