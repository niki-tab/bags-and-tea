<?php

namespace Src\Order\Application;

use Illuminate\Support\Facades\DB;

class CalculateOrderFees
{
    public function __invoke(float $subtotal, ?string $countryCode = null): array
    {
        // Get active marketplace fees (exclude soft deleted)
        $marketplaceFees = DB::table('marketplace_fees')
            ->where('is_active', true)
            ->where('visible_to_customer', true)
            ->whereNull('deleted_at') // Exclude soft deleted fees
            ->where(function ($query) {
                $query->whereNull('effective_from')
                    ->orWhere('effective_from', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('effective_until')
                    ->orWhere('effective_until', '>=', now());
            })
            ->orderBy('display_order')
            ->get();

        $fees = [];
        $totalFees = 0;

        foreach ($marketplaceFees as $fee) {
            $feeAmount = 0;

            if ($fee->fee_type === 'fixed') {
                $feeAmount = $fee->fixed_amount;
            } elseif ($fee->fee_type === 'percentage') {
                $feeAmount = $subtotal * ($fee->percentage_rate / 100);
                
                // Apply maximum fee limit if set
                if ($fee->maximum_fee_amount && $feeAmount > $fee->maximum_fee_amount) {
                    $feeAmount = $fee->maximum_fee_amount;
                }
            }

            // Apply minimum order amount restriction if set
            if ($fee->minimum_order_amount && $subtotal < $fee->minimum_order_amount) {
                continue;
            }

            // Use translation key if customer_label looks like a translation key, otherwise use as-is
            $displayName = $fee->customer_label ?: $fee->fee_name;
            if ($fee->fee_code === 'buyer_protection') {
                $displayName = trans('components/cart.buyer-protection');
            } elseif ($fee->fee_code === 'payment_processing') {
                $displayName = trans('components/cart.payment-processing');
            }
            
            $fees[] = [
                'code' => $fee->fee_code,
                'name' => $displayName,
                'amount' => $feeAmount,
                'type' => $fee->fee_type,
            ];

            $totalFees += $feeAmount;
        }

        // Calculate shipping if country code is provided
        $shipping = null;
        if ($countryCode) {
            $shipping = $this->calculateShipping($countryCode);
        }

        return [
            'fees' => $fees,
            'total_fees' => $totalFees,
            'shipping' => $shipping,
        ];
    }

    private function calculateShipping(string $countryCode): ?array
    {
        // Handle default shipping when no location consent
        if ($countryCode === 'DEFAULT') {
            return [
                'rate_name' => 'Standard Shipping',
                'zone_name' => 'Location not specified',
                'amount' => 0.00, // Free shipping as default until location is set
                'delivery_days_min' => null,
                'delivery_days_max' => null,
                'is_default' => true,
                'note' => 'shipping_calculated', // Translation key
            ];
        }

        // European countries - must have explicit shipping rates, no fallback to wildcard
        $europeanCountries = [
            'AD', 'AL', 'AT', 'BA', 'BE', 'BG', 'BY', 'CH', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 
            'FO', 'FR', 'GB', 'GI', 'GL', 'GR', 'HR', 'HU', 'IE', 'IS', 'IT', 'LI', 'LT', 'LU', 'LV', 
            'MC', 'MD', 'ME', 'MK', 'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'RS', 'RU', 'SE', 'SI', 'SK', 
            'SM', 'UA', 'VA'
        ];

        // Try to find specific country rate first
        $shippingRate = DB::table('shipping_rates')
            ->where('is_active', true)
            ->where('country_code', strtoupper($countryCode))
            ->orderBy('priority')
            ->first();

        // If no specific rate found, check if country is restricted
        if (!$shippingRate) {
            // Countries we don't ship to due to sanctions, conflicts, or safety concerns
            $restrictedCountries = [
                'RU', // Russia
                'BY', // Belarus
                'KP', // North Korea
                'SY', // Syria
                'IR', // Iran
                'CU', // Cuba
                'VE', // Venezuela
                'MM', // Myanmar
                'AF', // Afghanistan
                'YE', // Yemen
                'SD', // Sudan
                'SS', // South Sudan
                'SO', // Somalia
                'LY', // Libya
            ];

            // If country is restricted, shipping is not available
            if (in_array(strtoupper($countryCode), $restrictedCountries)) {
                return [
                    'rate_name' => 'Shipping Unavailable',
                    'zone_name' => 'Not Available',
                    'amount' => 0,
                    'delivery_days_min' => null,
                    'delivery_days_max' => null,
                    'is_available' => false,
                    'error_message' => 'shipping_not_available',
                ];
            }

            // For all other countries without specific rates, apply fixed 40 EUR international shipping
            return [
                'rate_name' => 'International Shipping',
                'zone_name' => $this->getCountryName($countryCode),
                'amount' => 40.00,
                'delivery_days_min' => 10,
                'delivery_days_max' => 15,
                'is_available' => true,
                'is_default' => false,
                'requires_customs_notice' => true,
            ];
        }


        return [
            'rate_name' => $shippingRate->rate_name,
            'zone_name' => $this->getCountryName($countryCode), // Show actual country name instead of zone
            'amount' => $shippingRate->base_rate,
            'delivery_days_min' => $shippingRate->delivery_days_min,
            'delivery_days_max' => $shippingRate->delivery_days_max,
            'is_available' => true,
            'is_default' => false,
        ];
    }

    private function getCountryName(string $countryCode): string
    {
        // Use the checkout translation file which already has country names
        $translationKey = 'components/checkout.countries.' . $countryCode;
        $translatedName = trans($translationKey);
        
        // If translation exists, use it; otherwise fallback to country code
        return $translatedName !== $translationKey ? $translatedName : $countryCode;
    }
}