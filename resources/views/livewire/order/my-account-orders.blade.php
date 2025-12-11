<div>
    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="border border-gray-200 bg-white">
                    <!-- Order Header -->
                    <div class="bg-background-color-4 px-4 py-3 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <div>
                                <span class="text-sm text-gray-500">{{ trans('my-account.order_number') }}:</span>
                                <span class="font-semibold text-theme-color-2 font-robotoCondensed ml-1">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2 items-center">
                                <span class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $this->getStatusColor($order->status) }}">
                                    {{ trans('my-account.order_status.' . $order->status) }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $this->getPaymentStatusColor($order->payment_status) }}">
                                    {{ trans('my-account.payment_status.' . $order->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="p-4">
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex gap-4">
                                    <!-- Product Image -->
                                    <div class="w-20 h-20 flex-shrink-0 bg-gray-100 overflow-hidden">
                                        @php
                                            $primaryImage = $item->product?->media?->where('is_primary', true)->first()
                                                         ?? $item->product?->media?->first();
                                        @endphp
                                        @if($primaryImage)
                                            <img src="{{ $primaryImage->full_url }}"
                                                 alt="{{ $item->product_name[app()->getLocale()] ?? $item->product_name['es'] ?? 'Product' }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-grow">
                                        <h4 class="font-medium text-theme-color-2 font-robotoCondensed">
                                            {{ $item->product_name[app()->getLocale()] ?? $item->product_name['es'] ?? 'Product' }}
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            {{ trans('my-account.quantity') }}: {{ $item->quantity }}
                                        </p>
                                        <p class="text-sm font-semibold text-color-2">
                                            {{ number_format($item->unit_price, 2) }} {{ $order->currency }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Total -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">{{ trans('my-account.order_total') }}:</span>
                                <span class="text-lg font-bold text-theme-color-2 font-robotoCondensed">
                                    {{ number_format($order->total_amount, 2) }} {{ $order->currency }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="mt-6">
                    {{ $orders->links('livewire.order.partials.pagination') }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM8 15a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ trans('my-account.no_orders') }}</h3>
            <p class="text-gray-500 mb-6">{{ trans('my-account.no_orders_description') }}</p>
            <a href="{{ route('shop.show.' . app()->getLocale(), ['locale' => app()->getLocale()]) }}" class="inline-block bg-color-2 text-white px-6 py-3 font-semibold hover:bg-theme-color-2 transition-colors font-robotoCondensed">
                {{ trans('my-account.start_shopping') }}
            </a>
        </div>
    @endif
</div>
