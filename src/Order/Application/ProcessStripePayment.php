<?php

namespace Src\Order\Application;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\CardException;
use Stripe\Exception\ApiErrorException;

class ProcessStripePayment
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(array $orderData): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => intval($orderData['total_amount'] * 100), // Convert to cents
                'currency' => strtolower($orderData['currency'] ?? 'EUR'),
                'payment_method_types' => $this->getPaymentMethodTypes($orderData['payment_method']),
                'metadata' => [
                    'order_number' => $orderData['order_number'],
                    'customer_email' => $orderData['customer_email'],
                    'customer_name' => $orderData['customer_name'],
                ],
                'description' => "Order #{$orderData['order_number']} - {$orderData['customer_name']}",
                'receipt_email' => $orderData['customer_email'],
                'shipping' => [
                    'address' => [
                        'line1' => $orderData['shipping_address']['line1'],
                        'line2' => $orderData['shipping_address']['line2'] ?? null,
                        'city' => $orderData['shipping_address']['city'],
                        'postal_code' => $orderData['shipping_address']['postal_code'],
                        'country' => $orderData['shipping_address']['country'],
                        'state' => $orderData['shipping_address']['state'] ?? null,
                    ],
                    'name' => $orderData['customer_name'],
                ],
            ]);

            return [
                'success' => true,
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'status' => $paymentIntent->status,
            ];

        } catch (CardException $e) {
            return [
                'success' => false,
                'error' => 'card_error',
                'message' => $e->getError()->message,
                'code' => $e->getError()->code,
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => 'api_error',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'general_error',
                'message' => 'An unexpected error occurred while processing payment.',
                'details' => $e->getMessage(),
            ];
        }
    }

    public function confirmPayment(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            return [
                'success' => true,
                'status' => $paymentIntent->status,
                'amount_received' => $paymentIntent->amount_received,
                'charges' => $paymentIntent->charges->data ?? [],
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => 'api_error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function cancelPayment(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            $paymentIntent->cancel();
            
            return [
                'success' => true,
                'status' => $paymentIntent->status,
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => 'api_error',
                'message' => $e->getMessage(),
            ];
        }
    }

    private function getPaymentMethodTypes(string $paymentMethod): array
    {
        switch ($paymentMethod) {
            case 'stripe_card':
                return ['card'];
            case 'stripe_paypal':
                return ['paypal'];
            case 'stripe_klarna':
                return ['klarna'];
            default:
                return ['card']; // Default fallback
        }
    }
}