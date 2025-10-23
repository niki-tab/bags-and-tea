@extends('layouts.admin.app')

@section('title', 'Create Order')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.orders') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Back to Orders
            </a>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-900">Create New Order</h2>
            </div>

            <form action="{{ route('admin.orders.store') }}" method="POST" class="px-6 py-6 space-y-8">
                @csrf

                <!-- Customer Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="customer_first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                            <input type="text" name="customer_first_name" id="customer_first_name" required
                                value="{{ old('customer_first_name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('customer_first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                            <input type="text" name="customer_last_name" id="customer_last_name" required
                                value="{{ old('customer_last_name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('customer_last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" name="customer_email" id="customer_email" required
                                value="{{ old('customer_email') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('customer_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                value="{{ old('customer_phone') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Billing Address -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="billing_line1" class="block text-sm font-medium text-gray-700">Address Line 1 *</label>
                            <input type="text" name="billing_address[line1]" id="billing_line1" required
                                value="{{ old('billing_address.line1') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="billing_line2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                            <input type="text" name="billing_address[line2]" id="billing_line2"
                                value="{{ old('billing_address.line2') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="billing_address[city]" id="billing_city" required
                                value="{{ old('billing_address.city') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="billing_postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                            <input type="text" name="billing_address[postal_code]" id="billing_postal_code" required
                                value="{{ old('billing_address.postal_code') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-gray-700">Country Code *</label>
                            <input type="text" name="billing_address[country]" id="billing_country" required maxlength="2"
                                value="{{ old('billing_address.country', 'ES') }}"
                                placeholder="ES"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">2-letter ISO country code (e.g., ES, FR, DE)</p>
                        </div>

                        <div>
                            <label for="billing_state" class="block text-sm font-medium text-gray-700">State/Province</label>
                            <input type="text" name="billing_address[state]" id="billing_state"
                                value="{{ old('billing_address.state') }}"
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
                                value="{{ old('shipping_address.line1') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="shipping_line2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                            <input type="text" name="shipping_address[line2]" id="shipping_line2"
                                value="{{ old('shipping_address.line2') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="shipping_address[city]" id="shipping_city" required
                                value="{{ old('shipping_address.city') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                            <input type="text" name="shipping_address[postal_code]" id="shipping_postal_code" required
                                value="{{ old('shipping_address.postal_code') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country Code *</label>
                            <input type="text" name="shipping_address[country]" id="shipping_country" required maxlength="2"
                                value="{{ old('shipping_address.country', 'ES') }}"
                                placeholder="ES"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">2-letter ISO country code (e.g., ES, FR, DE)</p>
                        </div>

                        <div>
                            <label for="shipping_state" class="block text-sm font-medium text-gray-700">State/Province</label>
                            <input type="text" name="shipping_address[state]" id="shipping_state"
                                value="{{ old('shipping_address.state') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                        <button type="button" onclick="addNewRow()" class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-xl font-bold">
                            +
                        </button>
                    </div>
                    <div id="productItemsContainer" class="space-y-4">
                        <div class="product-item-row p-4 bg-gray-50 rounded-md">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-12">
                                <div class="sm:col-span-1 flex items-end">
                                    <button type="button" onclick="deleteRow(this)" style="background-color: #ef4444 !important; color: white !important; padding: 0.5rem 0.75rem !important; border-radius: 0.375rem !important; font-size: 1.125rem !important; font-weight: 700 !important; display: block !important; width: 100% !important;">
                                        X
                                    </button>
                                </div>
                                <div class="sm:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">Select Product *</label>
                                    <select name="items[0][product_id]" required
                                        class="prod-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        onchange="calcPrice(this)">
                                        <option value="">Choose a product...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product['id'] }}"
                                                data-price="{{ $product['price'] }}"
                                                data-name="{{ $product['name'] }}"
                                                data-image="{{ $product['image'] }}">
                                                {{ $product['display_text'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="items[0][price]" class="price-hidden" required>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                                    <input type="number" name="items[0][quantity]" min="1" value="1" required
                                        class="qty-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        onchange="calcPrice(this)">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                    <input type="text" readonly class="subtotal-show mt-1 block w-full rounded-md border-gray-300 bg-gray-100" value="€0.00">
                                </div>
                            </div>
                        </div>
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
                                <option value="stripe_card">Stripe Card</option>
                                <option value="stripe_klarna">Stripe Klarna</option>
                                <option value="stripe_paypal">Stripe PayPal</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status *</label>
                            <select name="payment_status" id="payment_status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Order Status *</label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>

                        <div>
                            <label for="shipping_amount" class="block text-sm font-medium text-gray-700">Shipping Amount (EUR)</label>
                            <input type="number" name="shipping_amount" id="shipping_amount" step="0.01" min="0" value="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="tax_amount" class="block text-sm font-medium text-gray-700">Tax Amount (EUR)</label>
                            <input type="number" name="tax_amount" id="tax_amount" step="0.01" min="0" value="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var productsDataArray = @json($products);
var rowCounter = 1;

function calcPrice(element) {
    var row = element.closest('.product-item-row');
    var select = row.querySelector('.prod-select');
    var selectedOption = select.options[select.selectedIndex];
    var priceField = row.querySelector('.price-hidden');
    var qtyField = row.querySelector('.qty-input');
    var subtotalField = row.querySelector('.subtotal-show');

    var priceValue = selectedOption.getAttribute('data-price');
    if (priceValue) {
        priceField.value = priceValue;
        var qty = parseInt(qtyField.value) || 0;
        var price = parseFloat(priceValue) || 0;
        var subtotal = qty * price;
        subtotalField.value = '€' + subtotal.toFixed(2);
    }
}

function deleteRow(btn) {
    var allRows = document.querySelectorAll('.product-item-row');
    if (allRows.length > 1) {
        btn.closest('.product-item-row').remove();
    } else {
        alert('At least one item required');
    }
}

function addNewRow() {
    var container = document.getElementById('productItemsContainer');
    var opts = '';
    for (var i = 0; i < productsDataArray.length; i++) {
        var prod = productsDataArray[i];
        opts += '<option value="' + prod.id + '" data-price="' + prod.price + '">' + prod.display_text + '</option>';
    }

    var html = '<div class="product-item-row p-4 bg-gray-50 rounded-md">' +
        '<div class="grid grid-cols-1 gap-4 sm:grid-cols-12">' +
            '<div class="sm:col-span-1 flex items-end">' +
                '<button type="button" onclick="deleteRow(this)" style="background-color: #ef4444 !important; color: white !important; padding: 0.5rem 0.75rem !important; border-radius: 0.375rem !important; font-size: 1.125rem !important; font-weight: 700 !important; display: block !important; width: 100% !important;">X</button>' +
            '</div>' +
            '<div class="sm:col-span-6">' +
                '<label class="block text-sm font-medium text-gray-700">Select Product *</label>' +
                '<select name="items[' + rowCounter + '][product_id]" required class="prod-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="calcPrice(this)">' +
                    '<option value="">Choose a product...</option>' + opts +
                '</select>' +
                '<input type="hidden" name="items[' + rowCounter + '][price]" class="price-hidden" required>' +
            '</div>' +
            '<div class="sm:col-span-2">' +
                '<label class="block text-sm font-medium text-gray-700">Quantity *</label>' +
                '<input type="number" name="items[' + rowCounter + '][quantity]" min="1" value="1" required class="qty-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="calcPrice(this)">' +
            '</div>' +
            '<div class="sm:col-span-3">' +
                '<label class="block text-sm font-medium text-gray-700">Subtotal</label>' +
                '<input type="text" readonly class="subtotal-show mt-1 block w-full rounded-md border-gray-300 bg-gray-100" value="€0.00">' +
            '</div>' +
        '</div>' +
    '</div>';

    container.insertAdjacentHTML('beforeend', html);
    rowCounter++;
}

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
