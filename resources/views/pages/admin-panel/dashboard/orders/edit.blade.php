@extends('layouts.admin.app')

@section('title', 'Edit Order')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.orders') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Back to Orders
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Edit Order {{ $order->order_number }}</h2>
                    <p class="mt-1 text-sm text-gray-500">Created {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y H:i') }}</p>
                </div>
                <div class="flex flex-col gap-3">
                    <!-- Test Confirmations -->
                    <div class="flex gap-2 items-center">
                        <span class="text-xs font-medium text-gray-500 w-20">TEST:</span>
                        <form action="{{ route('admin.orders.send-test-confirmation', $order->order_number) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <button type="submit" class="px-3 py-1.5 bg-yellow-600 text-white text-xs rounded-md hover:bg-yellow-700">
                                🇬🇧 EN
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.send-test-confirmation', $order->order_number) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="es">
                            <button type="submit" class="px-3 py-1.5 bg-yellow-600 text-white text-xs rounded-md hover:bg-yellow-700">
                                🇪🇸 ES
                            </button>
                        </form>
                    </div>
                    <!-- Customer Confirmations (No Trustpilot) -->
                    <div class="flex gap-2 items-center">
                        <span class="text-xs font-medium text-gray-500 w-20">Customer:</span>
                        <form action="{{ route('admin.orders.send-confirmation', $order->order_number) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="include_trustpilot" value="0">
                            <input type="hidden" name="locale" value="en">
                            <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700">
                                🇬🇧 EN
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.send-confirmation', $order->order_number) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="include_trustpilot" value="0">
                            <input type="hidden" name="locale" value="es">
                            <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700">
                                🇪🇸 ES
                            </button>
                        </form>
                    </div>
                    <!-- Customer Confirmations (With Trustpilot) -->
                    <div class="flex gap-2 items-center">
                        <span class="text-xs font-medium text-gray-500 w-20">+ Trustpilot:</span>
                        <form action="{{ route('admin.orders.send-confirmation', $order->order_number) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="include_trustpilot" value="1">
                            <input type="hidden" name="locale" value="en">
                            <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-md hover:bg-green-700">
                                🇬🇧 EN
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.send-confirmation', $order->order_number) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="include_trustpilot" value="1">
                            <input type="hidden" name="locale" value="es">
                            <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-md hover:bg-green-700">
                                🇪🇸 ES
                            </button>
                        </form>
                    </div>
                    <!-- Certificate -->
                    <div class="flex gap-2 items-center">
                        <span class="text-xs font-medium text-gray-500 w-20">Certificate:</span>
                        @livewire('admin.orders.send-certificate', ['order' => $order])
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.orders.update', $order->order_number) }}" method="POST" class="px-6 py-6 space-y-8">
                @csrf
                @method('PUT')

                <!-- Customer Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Name *</label>
                            <input type="text" name="customer_name" id="customer_name" required
                                value="{{ old('customer_name', $order->customer_name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" name="customer_email" id="customer_email" required
                                value="{{ old('customer_email', $order->customer_email) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('customer_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                value="{{ old('customer_phone', $order->customer_phone) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                @php
                    // Handle billing address - could be string, array, or object
                    if (is_string($order->billing_address)) {
                        $billingAddress = json_decode($order->billing_address, true);
                    } elseif (is_object($order->billing_address)) {
                        $billingAddress = (array) $order->billing_address;
                    } else {
                        $billingAddress = $order->billing_address;
                    }

                    // Handle shipping address - could be string, array, or object
                    if (is_string($order->shipping_address)) {
                        $shippingAddress = json_decode($order->shipping_address, true);
                    } elseif (is_object($order->shipping_address)) {
                        $shippingAddress = (array) $order->shipping_address;
                    } else {
                        $shippingAddress = $order->shipping_address;
                    }
                @endphp

                <!-- Billing Address -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="billing_line1" class="block text-sm font-medium text-gray-700">Address Line 1 *</label>
                            <input type="text" name="billing_address[line1]" id="billing_line1" required
                                value="{{ old('billing_address.line1', $billingAddress['line1'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="billing_line2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                            <input type="text" name="billing_address[line2]" id="billing_line2"
                                value="{{ old('billing_address.line2', $billingAddress['line2'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="billing_address[city]" id="billing_city" required
                                value="{{ old('billing_address.city', $billingAddress['city'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="billing_postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                            <input type="text" name="billing_address[postal_code]" id="billing_postal_code" required
                                value="{{ old('billing_address.postal_code', $billingAddress['postal_code'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-gray-700">Country Code *</label>
                            <input type="text" name="billing_address[country]" id="billing_country" required maxlength="2"
                                value="{{ old('billing_address.country', $billingAddress['country'] ?? 'ES') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">2-letter ISO country code (e.g., ES, FR, DE)</p>
                        </div>

                        <div>
                            <label for="billing_state" class="block text-sm font-medium text-gray-700">State/Province</label>
                            <input type="text" name="billing_address[state]" id="billing_state"
                                value="{{ old('billing_address.state', $billingAddress['state'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Shipping Address</h3>
                        <button type="button" onclick="copyBillingToShipping()"
                            class="text-sm text-indigo-600 hover:text-indigo-900">
                            Copy from Billing
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="shipping_line1" class="block text-sm font-medium text-gray-700">Address Line 1 *</label>
                            <input type="text" name="shipping_address[line1]" id="shipping_line1" required
                                value="{{ old('shipping_address.line1', $shippingAddress['line1'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="shipping_line2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                            <input type="text" name="shipping_address[line2]" id="shipping_line2"
                                value="{{ old('shipping_address.line2', $shippingAddress['line2'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="shipping_address[city]" id="shipping_city" required
                                value="{{ old('shipping_address.city', $shippingAddress['city'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                            <input type="text" name="shipping_address[postal_code]" id="shipping_postal_code" required
                                value="{{ old('shipping_address.postal_code', $shippingAddress['postal_code'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country Code *</label>
                            <input type="text" name="shipping_address[country]" id="shipping_country" required maxlength="2"
                                value="{{ old('shipping_address.country', $shippingAddress['country'] ?? 'ES') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">2-letter ISO country code (e.g., ES, FR, DE)</p>
                        </div>

                        <div>
                            <label for="shipping_state" class="block text-sm font-medium text-gray-700">State/Province</label>
                            <input type="text" name="shipping_address[state]" id="shipping_state"
                                value="{{ old('shipping_address.state', $shippingAddress['state'] ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Order Items (Read-only) -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if(isset($order->suborders) && is_array($order->suborders))
                                    @foreach($order->suborders as $suborder)
                                        @if(isset($suborder->orderItems) || isset($suborder->order_items))
                                            @php
                                                $items = $suborder->orderItems ?? $suborder->order_items ?? [];
                                                if (is_object($items)) {
                                                    $items = (array) $items;
                                                }
                                            @endphp
                                            @foreach($items as $item)
                                                @php
                                                    $itemObj = is_object($item) ? $item : (object) $item;

                                                    // Properly convert product_snapshot to array, handling nested objects
                                                    if (is_string($itemObj->product_snapshot ?? null)) {
                                                        $snapshot = json_decode($itemObj->product_snapshot, true);
                                                    } elseif (is_object($itemObj->product_snapshot ?? null) || is_array($itemObj->product_snapshot ?? null)) {
                                                        // Convert to JSON and back to ensure all nested objects become arrays
                                                        $snapshot = json_decode(json_encode($itemObj->product_snapshot), true);
                                                    } else {
                                                        $snapshot = $itemObj->product_snapshot ?? [];
                                                    }

                                                    // Safely get the product name
                                                    $productName = 'N/A';
                                                    if (isset($snapshot['name'])) {
                                                        if (is_array($snapshot['name'])) {
                                                            $productName = $snapshot['name']['en'] ?? $snapshot['name'][array_key_first($snapshot['name'])] ?? 'N/A';
                                                        } elseif (is_string($snapshot['name'])) {
                                                            $productName = $snapshot['name'];
                                                        }
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $productName }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $itemObj->quantity ?? 0 }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€{{ number_format($itemObj->unit_price ?? 0, 2) }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€{{ number_format($itemObj->total_price ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 p-4 bg-gray-50 rounded-md">
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Subtotal:</dt>
                                <dd class="text-sm font-medium text-gray-900">€{{ number_format($order->subtotal, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Shipping:</dt>
                                <dd class="text-sm font-medium text-gray-900">€{{ number_format($order->shipping_amount, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Tax:</dt>
                                <dd class="text-sm font-medium text-gray-900">€{{ number_format($order->tax_amount, 2) }}</dd>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <dt class="text-base font-medium text-gray-900">Total:</dt>
                                <dd class="text-base font-bold text-gray-900">€{{ number_format($order->total_amount, 2) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Order Details -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Details</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                            <select name="payment_method" id="payment_method" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="stripe_card" {{ $order->payment_method === 'stripe_card' ? 'selected' : '' }}>Stripe Card</option>
                                <option value="stripe_klarna" {{ $order->payment_method === 'stripe_klarna' ? 'selected' : '' }}>Stripe Klarna</option>
                                <option value="stripe_paypal" {{ $order->payment_method === 'stripe_paypal' ? 'selected' : '' }}>Stripe PayPal</option>
                                <option value="cash" {{ $order->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ $order->payment_method === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="other" {{ $order->payment_method === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status *</label>
                            <select name="payment_status" id="payment_status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Order Status *</label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <div>
                            <label for="shipping_amount" class="block text-sm font-medium text-gray-700">Shipping Amount (EUR)</label>
                            <input type="number" name="shipping_amount" id="shipping_amount" step="0.01" min="0"
                                value="{{ old('shipping_amount', $order->shipping_amount) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="tax_amount" class="block text-sm font-medium text-gray-700">Tax Amount (EUR)</label>
                            <input type="number" name="tax_amount" id="tax_amount" step="0.01" min="0"
                                value="{{ old('tax_amount', $order->tax_amount) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $order->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyBillingToShipping() {
    document.getElementById('shipping_line1').value = document.getElementById('billing_line1').value;
    document.getElementById('shipping_line2').value = document.getElementById('billing_line2').value;
    document.getElementById('shipping_city').value = document.getElementById('billing_city').value;
    document.getElementById('shipping_postal_code').value = document.getElementById('billing_postal_code').value;
    document.getElementById('shipping_country').value = document.getElementById('billing_country').value;
    document.getElementById('shipping_state').value = document.getElementById('billing_state').value;
}
</script>
@endsection
