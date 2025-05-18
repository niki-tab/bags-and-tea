<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">{{ __('Featured Products') }}</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $product->name }}</h2>
                    
                    
                </div>
            </div>
        @endforeach
    </div>
</div>