<?php

namespace Src\Order\Application;

use Src\Order\Domain\OrderRepository;
use Src\Cart\Application\RetrieveCartContents;
use Src\Cart\Domain\CartRepository;
use Src\Order\Application\CalculateOrderFees;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;
use Src\Cart\Infrastructure\Eloquent\CartEloquentModel;
use Src\Order\Infrastructure\Eloquent\OrderEloquentModel;
use Ramsey\Uuid\Uuid;

class OrderCreator
{
    public function __construct(
        private OrderRepository $orderRepository,
        private CartRepository $cartRepository
    ) {}

    public function __invoke(
        array $customerData,
        array $billingAddress,
        array $shippingAddress,
        ?string $userId = null,
        ?string $sessionId = null,
        ?string $discountCode = null,
        ?string $paymentMethod = null
    ): array {
        // Retrieve cart contents
        $retrieveCartContents = new RetrieveCartContents($this->cartRepository);
        $cartData = $retrieveCartContents($userId, $sessionId);
        
        if (empty($cartData['items'])) {
            throw new \InvalidArgumentException('Cart is empty');
        }

        // Generate order number
        $orderNumber = $this->generateOrderNumber();
        
        // Group cart items by vendor
        $vendorGroups = $this->groupItemsByVendor($cartData['items']);
        
        // Calculate totals
        $totals = $this->calculateOrderTotals($vendorGroups, $shippingAddress['country'], $discountCode);
        
        // Create main order
        $orderData = [
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $userId,
            'order_number' => $orderNumber,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $paymentMethod,
            'currency' => 'EUR',
            'customer_email' => $customerData['email'],
            'customer_name' => $customerData['name'],
            'customer_phone' => $customerData['phone'] ?? null,
            'billing_address' => $billingAddress,
            'shipping_address' => $shippingAddress,
            'subtotal' => $totals['subtotal'],
            'total_discounts' => $totals['total_discounts'],
            'total_fees' => $totals['total_fees'],
            'shipping_amount' => $totals['shipping_amount'],
            'tax_amount' => $totals['tax_amount'],
            'total_amount' => $totals['total_amount'],
        ];
        
        $order = $this->orderRepository->createOrder($orderData);
        
        // Store individual fees
        if (!empty($totals['fees'])) {
            foreach ($totals['fees'] as $fee) {
                $this->createOrderFee($order['id'], $fee, $totals['subtotal']);
            }
        }
        
        // Create suborders for each vendor
        foreach ($vendorGroups as $vendorId => $items) {
            $this->createSuborderForVendor($order['id'], $vendorId, $items, $totals);
        }
        
        // Update product stock and clear cart after successful order creation
        $this->updateProductStock($cartData['items']);
        $this->clearCart($userId, $sessionId);
        
        return [
            'order_id' => $order['id'],
            'order_number' => $orderNumber,
            'total_amount' => $totals['total_amount'],
            'currency' => 'EUR'
        ];
    }

    private function generateOrderNumber(): string
    {
        $currentYear = date('Y');
        $prefix = 'BT-' . $currentYear . '-';
        
        // Get the highest order number for the current year
        $lastOrder = OrderEloquentModel::where('order_number', 'LIKE', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();
        
        if ($lastOrder) {
            // Extract the sequential number from the last order number
            $lastNumber = (int) substr($lastOrder->order_number, -6);
            $nextNumber = $lastNumber + 1;
        } else {
            // First order of the year
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    private function groupItemsByVendor(array $cartItems): array
    {
        $vendorGroups = [];
        
        foreach ($cartItems as $item) {
            $vendorId = $item['product']['vendor_id'];
            
            if (!isset($vendorGroups[$vendorId])) {
                $vendorGroups[$vendorId] = [];
            }
            
            $vendorGroups[$vendorId][] = $item;
        }
        
        return $vendorGroups;
    }

    private function calculateOrderTotals(array $vendorGroups, string $country, ?string $discountCode): array
    {
        $subtotal = 0;
        
        // Calculate subtotal
        foreach ($vendorGroups as $items) {
            foreach ($items as $item) {
                $subtotal += $item['product']['price'] * $item['quantity'];
            }
        }
        
        // Use the same fee calculation service as the checkout page
        $calculateOrderFees = new CalculateOrderFees();
        $feeResult = $calculateOrderFees($subtotal, $country);
        
        $totalFees = $feeResult['total_fees'];
        $shippingAmount = $feeResult['shipping']['amount'] ?? 0;
        
        // TODO: Implement discount calculation
        $totalDiscounts = 0;
        
        // TODO: Implement tax calculation
        $taxAmount = 0;
        
        $totalAmount = $subtotal - $totalDiscounts + $totalFees + $shippingAmount + $taxAmount;
        
        return [
            'subtotal' => $subtotal,
            'total_discounts' => $totalDiscounts,
            'total_fees' => $totalFees,
            'fees' => $feeResult['fees'], // Include individual fees
            'shipping_amount' => $shippingAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ];
    }

    private function createSuborderForVendor(string $orderId, string $vendorId, array $items, array $totals): void
    {
        $suborderSubtotal = 0;
        foreach ($items as $item) {
            $suborderSubtotal += $item['product']['price'] * $item['quantity'];
        }
        
        // Get vendor commission rate (placeholder - should come from vendor settings)
        $commissionRate = 5.00; // 5%
        $commissionAmount = ($suborderSubtotal * $commissionRate) / 100;
        $vendorPayout = $suborderSubtotal - $commissionAmount;
        
        $suborderData = [
            'id' => Uuid::uuid4()->toString(),
            'order_id' => $orderId,
            'vendor_id' => $vendorId,
            'suborder_number' => $this->generateSuborderNumber(),
            'status' => 'pending',
            'subtotal' => $suborderSubtotal,
            'vendor_commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'vendor_payout' => $vendorPayout,
        ];
        
        $suborder = $this->orderRepository->createSuborder($suborderData);
        
        // Create order items
        foreach ($items as $item) {
            $this->createOrderItem($suborder['id'], $item);
        }
        
        // Create vendor commission record
        $this->createVendorCommission($suborder['id'], $vendorId, $suborderSubtotal, $commissionRate);
    }

    private function generateSuborderNumber(): string
    {
        return 'SUB-' . date('Y') . '-' . str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function createOrderItem(string $suborderId, array $cartItem): void
    {
        $orderItemData = [
            'id' => Uuid::uuid4()->toString(),
            'suborder_id' => $suborderId,
            'product_id' => $cartItem['product_id'],
            'product_name' => $cartItem['product']['name'],
            'product_sku' => $cartItem['product']['sku'] ?? null,
            'unit_price' => $cartItem['product']['price'],
            'quantity' => $cartItem['quantity'],
            'total_price' => $cartItem['product']['price'] * $cartItem['quantity'],
            'product_snapshot' => $cartItem['product'], // Store full product data for history
        ];
        
        $this->orderRepository->createOrderItem($orderItemData);
    }

    private function createVendorCommission(string $suborderId, string $vendorId, float $orderAmount, float $commissionRate): void
    {
        $commissionAmount = ($orderAmount * $commissionRate) / 100;
        $vendorPayout = $orderAmount - $commissionAmount;
        
        $commissionData = [
            'id' => Uuid::uuid4()->toString(),
            'suborder_id' => $suborderId,
            'vendor_id' => $vendorId,
            'order_amount' => $orderAmount,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'vendor_payout' => $vendorPayout,
            'payout_status' => 'pending',
        ];
        
        $this->orderRepository->createVendorCommission($commissionData);
    }

    private function createOrderFee(string $orderId, array $fee, float $appliedToAmount): void
    {
        $orderFeeData = [
            'id' => Uuid::uuid4()->toString(),
            'order_id' => $orderId,
            'marketplace_fee_id' => null, // TODO: Get from fee data if available
            'fee_code' => $fee['code'],
            'fee_name' => $fee['name'], // This will be the translated name
            'fee_type' => $fee['type'],
            'fee_rate' => null, // TODO: Calculate if percentage fee
            'applied_to_amount' => $appliedToAmount,
            'fee_amount' => $fee['amount'],
            'visible_to_customer' => true,
            'calculation_details' => null,
        ];

        $this->orderRepository->createOrderFee($orderFeeData);
    }

    private function updateProductStock(array $cartItems): void
    {
        foreach ($cartItems as $item) {
            $product = ProductEloquentModel::find($item['product_id']);
            
            if ($product) {
                // Update stock
                $newStock = max(0, $product->stock - $item['quantity']);
                $product->stock = $newStock;
                
                // Set out_of_stock and is_sold_out flags if stock reaches 0
                if ($newStock <= 0) {
                    $product->out_of_stock = true;
                    $product->is_sold_out = true;
                }
                
                $product->save();
            }
        }
    }

    private function clearCart(?string $userId, ?string $sessionId): void
    {
        $query = CartEloquentModel::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        $query->delete();
    }
}