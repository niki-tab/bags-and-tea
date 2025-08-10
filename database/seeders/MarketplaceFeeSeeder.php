<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class MarketplaceFeeSeeder extends Seeder
{
    public function run(): void
    {
        $fees = [
            [
                'id' => Uuid::uuid4()->toString(),
                'fee_name' => json_encode([
                    'en' => 'Buyer Protection',
                    'es' => 'Protección del Comprador'
                ]),
                'fee_code' => 'buyer_protection',
                'description' => 'Protection fee that covers purchase protection, secure payment processing, and customer support for marketplace transactions.',
                'fee_type' => 'fixed',
                'fixed_amount' => 5.00,
                'percentage_rate' => null,
                'tiered_rates' => null,
                'minimum_order_amount' => null,
                'maximum_fee_amount' => null,
                'applicable_countries' => null, // Applies to all countries
                'applicable_payment_methods' => null, // Applies to all payment methods
                'is_active' => true,
                'effective_from' => null,
                'effective_until' => null,
                'visible_to_customer' => true,
                'customer_label' => 'Buyer Protection',
                'display_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'fee_name' => json_encode([
                    'en' => 'Payment Processing',
                    'es' => 'Procesamiento de Pago'
                ]),
                'fee_code' => 'payment_processing',
                'description' => 'Payment processing fee for secure credit card and payment method handling.',
                'fee_type' => 'percentage',
                'fixed_amount' => null,
                'percentage_rate' => 2.9000, // 2.9%
                'tiered_rates' => null,
                'minimum_order_amount' => null,
                'maximum_fee_amount' => 10.00, // Cap at €10
                'applicable_countries' => null,
                'applicable_payment_methods' => json_encode(['stripe_card', 'stripe_paypal']),
                'is_active' => false, // Disabled for now
                'effective_from' => null,
                'effective_until' => null,
                'visible_to_customer' => false, // Hidden from customer
                'customer_label' => 'Payment Processing',
                'display_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('marketplace_fees')->insert($fees);

        $this->command->info('Marketplace fees created successfully:');
        $this->command->info('- Buyer Protection: €5.00 (active, visible)');
        $this->command->info('- Payment Processing: 2.9% capped at €10 (inactive, hidden)');
    }
}