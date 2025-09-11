<div class="bg-background-color-4 min-h-screen">
    <div class="container mx-auto px-4 py-14 max-w-7xl">
    @if($product)
        <!-- Product Title - Above everything -->
        <h1 class="text-3xl lg:text-4xl font-light text-gray-800 mb-8 text-center font-['Lovera']" style="color: #482626;">
            @php
                //$brand = strtoupper($product->brand ? $product->brand->name : '');
                $brand = '';
                $name = strtoupper($product->getTranslation('name', app()->getLocale()));
                $fullTitle = trim($brand . ' ' . $name);
                
                // Replace numbers with spans that use RobotoCondensed font and medium weight
                $titleWithNumberFonts = preg_replace('/(\d+)/', '<span class="font-robotoCondensed font-medium">$1</span>', $fullTitle);
            @endphp
            {!! $titleWithNumberFonts !!}
        </h1>
        
        <!-- Breadcrumb Navigation -->
        @if(!empty($breadcrumbs))
            <nav class="flex justify-center mb-6">
                <ol class="flex items-center space-x-1 lg:space-x-2 text-xs lg:text-sm">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        <li class="flex items-center">
                            @if($index > 0)
                                <svg class="w-4 h-4 mx-1 lg:mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            
                            @if($breadcrumb['url'] && !($breadcrumb['is_current'] ?? false))
                                <a href="{{ $breadcrumb['url'] }}" class="text-color-2 hover:text-[#AC2231] transition-colors">
                                    {{ $breadcrumb['text'] }}
                                </a>
                            @else
                                <span class="text-color-2">
                                    {{ $breadcrumb['text'] }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
        
        <!-- Product Specifications - Below title, above grid (Desktop only) -->
        <div class="hidden lg:flex lg:justify-center mb-8 text-sm w-96 lg:w-auto max-w-6xl mx-auto mt-10 gap-10">
            <div class="text-left whitespace-nowrap lg:mr-8">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Estado:' : 'Condition:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['estado'] ?? 'N/A' }}</span>
            </div>
            <div class="text-left whitespace-nowrap lg:mr-8">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Año:' : 'Year:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['ano'] ?? 'N/A' }}</span>
            </div>
            <div class="text-left whitespace-nowrap lg:mr-8">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Color:' : 'Color:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['color'] ?? 'N/A' }}</span>
            </div>
            <div class="text-left whitespace-nowrap">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Tamaño (cm):' : 'Size (cm):' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['tamano'] ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-[10%_45%_35%] gap-8 mb-16 mt-10 lg:mt-14" 
             x-data="{ 
                 mainImageHeight: 0,
                 updateHeight() {
                     this.mainImageHeight = this.$refs.mainImage.offsetHeight;
                 }
             }" 
             x-init="
                 $nextTick(() => { updateHeight(); });
                 window.addEventListener('resize', () => { updateHeight(); });
                 new ResizeObserver(() => { updateHeight(); }).observe($refs.mainImage);
             ">
            <!-- Column 1: Thumbnail Carousel (20%) -->
            <div class="flex flex-col">
                <div class="flex lg:flex-col gap-3 overflow-x-auto lg:overflow-y-auto lg:overflow-x-visible h-auto lg:h-auto" 
                     :style="window.innerWidth >= 1024 ? `height: ${mainImageHeight}px` : ''"
                     x-ref="thumbnailContainer">
                    @if(!empty($productImages))
                        @foreach($productImages as $index => $image)
                            <div class="flex-shrink-0 cursor-pointer transition-all duration-200 hover:opacity-75 {{ $currentImageIndex === $index ? 'border-2 border-color-2' : 'border-2 border-transparent' }}"
                                 wire:click="setCurrentImage({{ $index }})">
                                <img src="{{ str_starts_with($image['file_path'], 'https://') || str_contains($image['file_path'], 'r2.cloudflarestorage.com') ? $image['file_path'] : asset($image['file_path']) }}" 
                                     alt="Product image {{ $index + 1 }}" 
                                     class="w-24 lg:w-full object-contain bg-transparent rounded-lg aspect-square">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Column 2: Main Image Display (50%) -->
            <div class="relative">
                <div class="relative bg-transparent aspect-square mx-auto" x-ref="mainImage">
                    @if(!empty($productImages) && isset($productImages[$currentImageIndex]))
                        <img src="{{ str_starts_with($productImages[$currentImageIndex]['file_path'], 'https://') || str_contains($productImages[$currentImageIndex]['file_path'], 'r2.cloudflarestorage.com') ? $productImages[$currentImageIndex]['file_path'] : asset($productImages[$currentImageIndex]['file_path']) }}" 
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
                <div class="text-3xl font-robotoCondensed text-[#CA2530] mb-8 lg:mb-10 text-left w-full max-w-md mx-auto lg:max-w-none lg:mx-0 lg:w-auto px-7 md:px-10 lg:px-0 lg:ml-14">
                    € {{ number_format($product->price, 2, ',', '.') }}
                    <span class="text-xs font-normal align-baseline">{{ trans('components/cart.vat-included') }}</span>
                </div>

                <!-- Action Buttons -->
                <div class="mb-8 w-full max-w-md mx-auto lg:mx-0 lg:ml-5 px-7 md:px-10">
                    @livewire('cart.add-to-cart-button', [
                        'productId' => $product->id, 
                        'buttonText' => trans('components/cart.add-to-cart'),
                        'isSoldOut' => $product->is_sold_out || $product->out_of_stock,
                        'isHidden' => $product->is_hidden
                    ])
                </div>

                    <!-- Product Specifications - Mobile only, below add-to-cart button -->
                <div class="lg:hidden grid grid-cols-1 gap-4 mb-8 text-sm w-full max-w-md mx-auto lg:mx-0 px-8 md:px-10">
                    <div class="text-left whitespace-nowrap">
                        <span class="text-color-2 font-medium text-base">{{ app()->getLocale() === 'es' ? 'Estado:' : 'Condition:' }}</span>
                        <span class="font-medium text-base text-[#AC2231]"> {{ $specifications['estado'] ?? 'N/A' }}</span>
                    </div>
                    <div class="text-left whitespace-nowrap">
                        <span class="text-color-2 font-medium text-base">{{ app()->getLocale() === 'es' ? 'Año:' : 'Year:' }}</span>
                        <span class="font-medium text-base text-[#AC2231]"> {{ $specifications['ano'] ?? 'N/A' }}</span>
                    </div>
                    <div class="text-left whitespace-nowrap">
                        <span class="text-color-2 font-medium text-base">{{ app()->getLocale() === 'es' ? 'Color:' : 'Color:' }}</span>
                        <span class="font-medium text-base text-[#AC2231]"> {{ $specifications['color'] ?? 'N/A' }}</span>
                    </div>
                    <div class="text-left whitespace-nowrap">
                        <span class="text-color-2 font-medium text-base">{{ app()->getLocale() === 'es' ? 'Tamaño (cm):' : 'Size (cm):' }}</span>
                        <span class="font-medium text-base text-[#AC2231]"> {{ $specifications['tamano'] ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Certificado Autenticidad -->
                <div style="background-color: #C12637; padding: 1rem;" class="flex flex-col justify-center mx-auto ml-0 lg:ml-5 w-full mb-8">
                    <span style="color: white;" class="font-medium mb-2 ml-4">{{ __('pages/product-detail.certificate-title') }}</span>
                    <div class="flex ml-4">
                        <img src="{{ asset('images/icons/safe_icon.svg') }}" alt="Safe icon" class="w-10 h-10 mr-2">
                        <span style="color: white;" class="text-sm font-normal">{{ __('pages/product-detail.certificate-text') }}</span>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="prose prose-gray max-w-none ml-4">
                    <p class="text-gray-600 leading-relaxed">
                        {{ $product->getTranslation('description_1', app()->getLocale()) ?: $product->getTranslation('description_2', app()->getLocale()) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Additional Product Information Section -->
        @livewire('src.products.product.additional-information')

    @else
        <div class="text-center py-16">
            <h1 class="text-2xl text-gray-600">
                {{ app()->getLocale() === 'es' ? 'Producto no encontrado' : 'Product not found' }}
            </h1>
        </div>
    @endif
    </div>
</div>