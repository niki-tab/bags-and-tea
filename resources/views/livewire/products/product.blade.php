<div class="bg-background-color-4 min-h-screen">
    <div class="container mx-auto px-4 py-14 max-w-7xl">
    @if($product)
        <!-- Product Title - Above everything -->
        <h1 class="text-3xl lg:text-4xl font-light text-gray-800 mb-8 text-center font-['Lovera'] mb-14" style="color: #482626;">
            {{ strtoupper($product->brand ? $product->brand->name : '') }} {{ strtoupper($product->getTranslation('name', app()->getLocale())) }}
        </h1>
        
        <!-- Product Specifications - Below title, above grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-16 mb-8 text-sm w-96 lg:w-auto max-w-6xl mx-auto">
            <div class="text-left whitespace-nowrap">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Estado:' : 'Condition:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['estado'] ?? 'N/A' }}</span>
            </div>
            <div class="text-left whitespace-nowrap">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Año:' : 'Year:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['ano'] ?? 'N/A' }}</span>
            </div>
            <div class="text-left whitespace-nowrap">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Color:' : 'Color:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['color'] ?? 'N/A' }}</span>
            </div>
            <div class="text-left whitespace-nowrap">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Tamaño (cm):' : 'Size (cm):' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['tamano'] ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-[10%_50%_30%] gap-8 mb-16 mt-14">
            <!-- Column 1: Thumbnail Carousel (20%) -->
            <div class="flex flex-col">
                <div class="flex lg:flex-col gap-3 lg:h-96 overflow-x-auto lg:overflow-y-auto lg:overflow-x-visible">
                    @if(!empty($productImages))
                        @foreach($productImages as $index => $image)
                            <div class="flex-shrink-0 cursor-pointer transition-all duration-200 hover:opacity-75 {{ $currentImageIndex === $index ? 'ring-2 ring-color-2' : '' }}"
                                 wire:click="setCurrentImage({{ $index }})">
                                <img src="{{ asset($image['file_path']) }}" 
                                     alt="Product image {{ $index + 1 }}" 
                                     class="w-24 h-24 lg:w-full lg:h-24 object-contain bg-transparent rounded-lg">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Column 2: Main Image Display (50%) -->
            <div class="relative">
                <div class="relative bg-transparent aspect-square mx-auto">
                    @if(!empty($productImages) && isset($productImages[$currentImageIndex]))
                        <img src="{{ asset($productImages[$currentImageIndex]['file_path']) }}" 
                             alt="{{ $product->getTranslation('name', app()->getLocale()) }}" 
                             class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded-lg">
                            <span class="text-gray-400">{{ app()->getLocale() === 'es' ? 'Sin imagen disponible' : 'No image available' }}</span>
                        </div>
                    @endif
                    
                    <!-- Navigation arrows for main image -->
                    @if(count($productImages) > 1)
                        <button wire:click="previousImage" 
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-black bg-opacity-70 text-white rounded-full hover:bg-opacity-90 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button wire:click="nextImage" 
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-black bg-opacity-70 text-white rounded-full hover:bg-opacity-90 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    @endif
                </div>
                
                <!-- Dot indicators -->
                @if(count($productImages) > 1)
                    <div class="flex justify-center mt-6 space-x-2">
                        @foreach($productImages as $index => $image)
                            <button wire:click="setCurrentImage({{ $index }})" 
                                    class="w-3 h-3 rounded-full transition-all {{ $currentImageIndex === $index ? 'bg-color-2' : 'bg-gray-300' }}">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Column 3: Product Information (30%) -->
            <div class="flex flex-col">
                <!-- Price -->
                <div class="text-3xl font-robotoCondensed text-[#CA2530] mb-12 text-left ml-10">
                    € &nbsp;&nbsp;{{ number_format($product->price, 2, ',', '.') }}
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4 mb-12 flex flex-col items-center ml-10">
                    <button disabled class="w-full bg-gray-400 text-white py-4 px-6 text-lg font-medium cursor-not-allowed opacity-60 font-['Lora']">
                        {{ app()->getLocale() === 'es' ? 'Web en construcción' : 'Website under construction' }}
                    </button>
                    <button disabled class="w-full border-2 border-gray-400 text-gray-400 py-4 px-6 text-lg font-medium cursor-not-allowed opacity-60 font-['Lora']">
                        {{ app()->getLocale() === 'es' ? 'Web en construcción' : 'Website under construction' }}
                    </button>
                </div>

                <!-- Product Description -->
                <div class="prose prose-gray max-w-none ml-4">
                    <p class="text-gray-600 leading-relaxed">
                        {{ $product->getTranslation('description_1', app()->getLocale()) ?: $product->getTranslation('description_2', app()->getLocale()) }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-16">
            <h1 class="text-2xl text-gray-600">
                {{ app()->getLocale() === 'es' ? 'Producto no encontrado' : 'Product not found' }}
            </h1>
        </div>
    @endif
    </div>
</div>