<div>
    <!-- Send Certificate Button -->
    <button wire:click="openModal" type="button" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition duration-150">
        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Send Certificate
    </button>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Send Authentication Certificate
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Select a product from this order to send an authentication certificate to {{ $order->customer_email ?? $order->billing_email }}
                                </p>
                            </div>

                            <!-- Product List -->
                            <div class="mt-6 space-y-3">
                                @forelse($products as $index => $product)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $product['name'] }}</p>
                                            @if(isset($product['snapshot']['sku']))
                                                <p class="text-xs text-gray-500 mt-1">SKU: {{ $product['snapshot']['sku'] }}</p>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex flex-col gap-2">
                                            <!-- Customer Buttons -->
                                            <div class="flex gap-2">
                                                <button
                                                    wire:click="sendCertificate({{ $index }}, 'en')"
                                                    wire:loading.attr="disabled"
                                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                                    wire:target="sendCertificate({{ $index }}, 'en')"
                                                    class="px-3 py-1.5 bg-purple-600 text-white text-xs rounded-md hover:bg-purple-700 transition duration-150 disabled:opacity-50">
                                                    <span wire:loading.remove wire:target="sendCertificate({{ $index }}, 'en')">🇬🇧 Customer (EN)</span>
                                                    <span wire:loading wire:target="sendCertificate({{ $index }}, 'en')">Sending...</span>
                                                </button>
                                                <button
                                                    wire:click="sendCertificate({{ $index }}, 'es')"
                                                    wire:loading.attr="disabled"
                                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                                    wire:target="sendCertificate({{ $index }}, 'es')"
                                                    class="px-3 py-1.5 bg-purple-600 text-white text-xs rounded-md hover:bg-purple-700 transition duration-150 disabled:opacity-50">
                                                    <span wire:loading.remove wire:target="sendCertificate({{ $index }}, 'es')">🇪🇸 Customer (ES)</span>
                                                    <span wire:loading wire:target="sendCertificate({{ $index }}, 'es')">Enviando...</span>
                                                </button>
                                            </div>
                                            <!-- Test Buttons -->
                                            <div class="flex gap-2">
                                                <button
                                                    wire:click="sendTestCertificate({{ $index }}, 'en')"
                                                    wire:loading.attr="disabled"
                                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                                    wire:target="sendTestCertificate({{ $index }}, 'en')"
                                                    class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition duration-150 disabled:opacity-50">
                                                    <span wire:loading.remove wire:target="sendTestCertificate({{ $index }}, 'en')">🇬🇧 Test (EN)</span>
                                                    <span wire:loading wire:target="sendTestCertificate({{ $index }}, 'en')">Sending...</span>
                                                </button>
                                                <button
                                                    wire:click="sendTestCertificate({{ $index }}, 'es')"
                                                    wire:loading.attr="disabled"
                                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                                    wire:target="sendTestCertificate({{ $index }}, 'es')"
                                                    class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition duration-150 disabled:opacity-50">
                                                    <span wire:loading.remove wire:target="sendTestCertificate({{ $index }}, 'es')">🇪🇸 Test (ES)</span>
                                                    <span wire:loading wire:target="sendTestCertificate({{ $index }}, 'es')">Enviando...</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <p class="text-sm text-gray-500">No products found in this order.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Close Button -->
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button
                            wire:click="closeModal"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
</div>
