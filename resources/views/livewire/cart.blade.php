@section('footer-desktop-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

<div class="container mx-auto px-4 pl-16 md:py-8 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">{{ trans('components/cart.title') }}</h1>
    @if(count($cartItems) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cartItems as $item)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-48 object-cover">
                    
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $item['name'] }}</h2>
                        <p class="text-gray-600 mb-4">{{ $item['description'] }}</p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">{{ trans('components/cart.price') }} ${{ number_format($item['price'], 2) }}</span>
                            <button wire:click="removeItem('{{ $item['id'] }}')" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-600">
                                {{ trans('components/cart.remove') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 text-right">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ trans('components/cart.total') }} ${{ number_format($totalPrice, 2) }}</h2>
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-600">{{ trans('components/cart.checkout') }}</button>
        </div>
    @else
        <p class="text-gray-600">{{ trans('components/cart.empty') }}</p>
    @endif
</div>