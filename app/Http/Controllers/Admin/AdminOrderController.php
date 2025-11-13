<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewOrderCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;
use Src\Order\Domain\OrderRepository;
use Src\Products\Product\Domain\ProductRepository;

class AdminOrderController extends Controller
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository
    ) {}

    public function create(): View
    {
        // Get all products with stock
        $products = \DB::table('products')
            ->select([
                'products.id',
                'products.name',
                'products.sku',
                'products.price',
                'products.discounted_price',
                'products.stock',
                'product_media.file_path as image'
            ])
            ->leftJoin('product_media', function($join) {
                $join->on('products.id', '=', 'product_media.product_id')
                     ->where('product_media.is_primary', '=', 1);
            })
            ->where('products.status', 'approved')
            ->where('products.stock', '>', 0)
            ->orderBy('products.name')
            ->get();

        // Format products for view
        $formattedProducts = $products->map(function($product) {
            $name = json_decode($product->name, true);
            $displayName = is_array($name) ? ($name['en'] ?? $name[array_key_first($name)] ?? 'Unknown') : $product->name;
            $finalPrice = $product->discounted_price ?? $product->price;

            return [
                'id' => $product->id,
                'name' => $displayName,
                'sku' => $product->sku,
                'price' => $finalPrice,
                'original_price' => $product->price,
                'discount_price' => $product->discounted_price,
                'stock' => $product->stock,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'display_text' => $displayName . ' (SKU: ' . ($product->sku ?? 'N/A') . ') - €' . number_format($finalPrice, 2) . ($product->discounted_price ? ' (discounted)' : '') . ' - Stock: ' . $product->stock,
            ];
        });

        return view('pages.admin-panel.dashboard.orders.create', [
            'products' => $formattedProducts
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'billing_address.line1' => 'required|string|max:255',
            'billing_address.line2' => 'nullable|string|max:255',
            'billing_address.city' => 'required|string|max:100',
            'billing_address.postal_code' => 'required|string|max:20',
            'billing_address.country' => 'required|string|max:2',
            'billing_address.state' => 'nullable|string|max:100',
            'shipping_address.line1' => 'required|string|max:255',
            'shipping_address.line2' => 'nullable|string|max:255',
            'shipping_address.city' => 'required|string|max:100',
            'shipping_address.postal_code' => 'required|string|max:20',
            'shipping_address.country' => 'required|string|max:2',
            'shipping_address.state' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|uuid|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:stripe_card,stripe_klarna,stripe_paypal,cash,bank_transfer,other',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'shipping_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Generate order number
        $orderNumber = $this->generateOrderNumber();

        // Calculate subtotal
        $subtotal = collect($validated['items'])->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        $shippingAmount = $validated['shipping_amount'] ?? 0;
        $taxAmount = $validated['tax_amount'] ?? 0;
        $totalAmount = $subtotal + $shippingAmount + $taxAmount;

        // Add first_name and last_name to addresses to match checkout format
        $billingAddress = $validated['billing_address'];
        $billingAddress['first_name'] = $validated['customer_first_name'];
        $billingAddress['last_name'] = $validated['customer_last_name'];

        $shippingAddress = $validated['shipping_address'];
        $shippingAddress['first_name'] = $validated['customer_first_name'];
        $shippingAddress['last_name'] = $validated['customer_last_name'];

        // Create customer_name from first and last name
        $customerName = $validated['customer_first_name'] . ' ' . $validated['customer_last_name'];

        // Use database transaction to ensure all data is created properly
        \DB::beginTransaction();

        try {
            // Create order
            $orderId = Uuid::uuid4()->toString();
            $orderData = [
                'id' => $orderId,
                'user_id' => null,
                'order_number' => $orderNumber,
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'],
                'currency' => 'EUR',
                'customer_email' => $validated['customer_email'],
                'customer_name' => $customerName,
                'customer_phone' => $validated['customer_phone'],
                'billing_address' => $billingAddress, // Model will auto-cast to JSON
                'shipping_address' => $shippingAddress, // Model will auto-cast to JSON
                'subtotal' => $subtotal,
                'total_discounts' => 0,
                'total_fees' => 0,
                'shipping_amount' => $shippingAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'],
            ];

            $createdOrder = $this->orderRepository->createOrder($orderData);

            // Verify order was created
            if (!$createdOrder) {
                throw new \Exception('Failed to create order in database');
            }

            // Double-check the order exists in the database
            $orderCheck = \DB::table('orders')->where('id', $orderId)->first();
            if (!$orderCheck) {
                \Log::error('Order not found in database after creation', [
                    'order_id' => $orderId,
                    'order_number' => $orderNumber,
                    'created_order' => $createdOrder
                ]);
                throw new \Exception('Order was not saved to database. Order ID: ' . $orderId);
            }

        // Group items by vendor to create suborders
        $itemsByVendor = [];
        foreach ($validated['items'] as $item) {
            // Get product data using DB query
            $product = \DB::table('products')
                ->select('id', 'name', 'sku', 'vendor_id')
                ->where('id', $item['product_id'])
                ->first();

            if (!$product) {
                continue; // Skip if product not found
            }

            $vendorId = $product->vendor_id ?? null;

            if (!isset($itemsByVendor[$vendorId])) {
                $itemsByVendor[$vendorId] = [];
            }

            $itemsByVendor[$vendorId][] = [
                'item' => $item,
                'product' => $product
            ];
        }

        // Create suborders and order items
        foreach ($itemsByVendor as $vendorId => $items) {
            $suborderId = Uuid::uuid4()->toString();
            $suborderSubtotal = 0;

            foreach ($items as $itemData) {
                $suborderSubtotal += $itemData['item']['quantity'] * $itemData['item']['price'];
            }

            // Create suborder
            $suborderNumber = $orderNumber . '-' . substr($suborderId, 0, 8);
            $suborderData = [
                'id' => $suborderId,
                'order_id' => $orderId,
                'vendor_id' => $vendorId,
                'suborder_number' => $suborderNumber,
                'status' => $validated['status'],
                'subtotal' => $suborderSubtotal,
                'vendor_commission_rate' => 0,
                'commission_amount' => 0,
                'vendor_payout' => $suborderSubtotal,
            ];

            $this->orderRepository->createSuborder($suborderData);

            // Create order items for this suborder
            foreach ($items as $itemData) {
                $item = $itemData['item'];
                $product = $itemData['product'];

                // Decode product name if it's JSON
                $productName = json_decode($product->name, true);
                if (!is_array($productName)) {
                    $productName = ['en' => $product->name];
                }

                $orderItem = [
                    'id' => Uuid::uuid4()->toString(),
                    'suborder_id' => $suborderId,
                    'product_id' => $item['product_id'],
                    'product_name' => json_encode($productName),
                    'product_sku' => $product->sku ?? null,
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['quantity'] * $item['price'],
                    'product_snapshot' => json_encode([
                        'name' => $productName,
                        'sku' => $product->sku ?? null,
                    ]),
                ];

                $this->orderRepository->createOrderItem($orderItem);

                // Reduce product stock
                \DB::table('products')
                    ->where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);

                // Check if stock is now 0 and update out_of_stock and is_sold_out flags
                $updatedProduct = \DB::table('products')
                    ->where('id', $item['product_id'])
                    ->first();

                if ($updatedProduct && $updatedProduct->stock <= 0) {
                    \DB::table('products')
                        ->where('id', $item['product_id'])
                        ->update([
                            'out_of_stock' => true,
                            'is_sold_out' => true,
                            'stock' => 0 // Ensure stock doesn't go negative
                        ]);
                }
            }
        }

            \DB::commit();

            return redirect()
                ->route('admin.orders.edit', ['orderNumber' => $orderNumber])
                ->with('success', 'Order created successfully!');

        } catch (\Exception $e) {
            \DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function edit(string $orderNumber): View
    {
        $orderData = $this->orderRepository->retrieveOrderByNumber($orderNumber);

        if (!$orderData) {
            abort(404, 'Order not found');
        }

        // Convert array to object recursively for easier access in the view
        $order = json_decode(json_encode($orderData));

        return view('pages.admin-panel.dashboard.orders.edit', [
            'order' => $order,
        ]);
    }

    public function update(Request $request, string $orderNumber): RedirectResponse
    {
        $orderData = $this->orderRepository->retrieveOrderByNumber($orderNumber);

        if (!$orderData) {
            abort(404, 'Order not found');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'billing_address.line1' => 'required|string|max:255',
            'billing_address.line2' => 'nullable|string|max:255',
            'billing_address.city' => 'required|string|max:100',
            'billing_address.postal_code' => 'required|string|max:20',
            'billing_address.country' => 'required|string|max:2',
            'billing_address.state' => 'nullable|string|max:100',
            'shipping_address.line1' => 'required|string|max:255',
            'shipping_address.line2' => 'nullable|string|max:255',
            'shipping_address.city' => 'required|string|max:100',
            'shipping_address.postal_code' => 'required|string|max:20',
            'shipping_address.country' => 'required|string|max:2',
            'shipping_address.state' => 'nullable|string|max:100',
            'payment_method' => 'required|in:stripe_card,stripe_klarna,stripe_paypal,cash,bank_transfer,other',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'shipping_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $shippingAmount = $validated['shipping_amount'] ?? 0;
        $taxAmount = $validated['tax_amount'] ?? 0;
        $totalAmount = $orderData['subtotal'] + $shippingAmount + $taxAmount;

        $updateData = [
            'customer_email' => $validated['customer_email'],
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'billing_address' => json_encode($validated['billing_address']),
            'shipping_address' => json_encode($validated['shipping_address']),
            'payment_method' => $validated['payment_method'],
            'shipping_amount' => $shippingAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'],
        ];

        $this->orderRepository->updateOrder($orderData['id'], $updateData);
        $this->orderRepository->updatePaymentStatus($orderData['id'], $validated['payment_status']);
        $this->orderRepository->updateOrderStatus($orderData['id'], $validated['status']);

        return redirect()
            ->route('admin.orders.edit', ['orderNumber' => $orderNumber])
            ->with('success', 'Order updated successfully!');
    }

    public function sendConfirmation(Request $request, string $orderNumber): RedirectResponse
    {
        $orderData = $this->orderRepository->retrieveOrderByNumber($orderNumber);

        if (!$orderData) {
            abort(404, 'Order not found');
        }

        try {
            $includeTrustpilot = $request->input('include_trustpilot', 1);

            // Detect the customer's locale from order data
            $customerLocale = 'en';
            // Try to get from user's preference first
            $user = \DB::table('users')->where('email', $orderData['customer_email'])->first();
            if ($user && isset($user->locale)) {
                $customerLocale = $user->locale;
            } else {
                // Fallback to shipping address country
                if (isset($orderData['shipping_address']['country']) && $orderData['shipping_address']['country'] === 'ES') {
                    $customerLocale = 'es';
                } else {
                    $customerLocale = 'en';
                }
            }

            if ($includeTrustpilot) {
                // Send with both admin and Trustpilot BCC (same as automatic orders)
                \Mail::to($orderData['customer_email'])
                    ->bcc(['nicolas.tabares.tech@gmail.com', 'bagsandtea.com+d98660a3fa@invite.trustpilot.com'])
                    ->send(new \App\Mail\OrderConfirmation($orderNumber, $customerLocale));

                $message = 'Order confirmation email sent successfully (with Trustpilot)!';
            } else {
                // Send only with admin BCC (no Trustpilot)
                \Mail::to($orderData['customer_email'])
                    ->bcc(['nicolas.tabares.tech@gmail.com'])
                    ->send(new \App\Mail\OrderConfirmation($orderNumber, $customerLocale));

                $message = 'Order confirmation email sent successfully (without Trustpilot)!';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send confirmation email: ' . $e->getMessage());
        }
    }

    private function generateOrderNumber(): string
    {
        $year = date('Y');
        $prefix = "BT-{$year}-";

        // Get the last order number for this year using direct DB query
        $lastOrder = \DB::table('orders')
            ->where('order_number', 'like', "{$prefix}%")
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}
