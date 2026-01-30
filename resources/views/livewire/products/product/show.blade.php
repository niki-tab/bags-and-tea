<div class="bg-background-color-4 min-h-screen">
    <div class="container mx-auto px-4 py-14 max-w-7xl">
    @if($product)
        <!-- Product Title - Above everything -->
        <h1 class="text-3xl lg:text-4xl font-light text-gray-800 mb-8 text-center font-rosaline" style="color: #482626;">
            @php
                //$brand = strtoupper($product->brand ? $product->brand->name : '');
                $brand = '';
                $name = strtoupper($product->getTranslation('name', app()->getLocale()));
                $fullTitle = trim($brand . ' ' . $name);
                
                // Replace numbers with spans that use RobotoCondensed font and medium weight
            
            @endphp
            {!! $fullTitle !!}
        </h1>
        
        <!-- Breadcrumb Navigation -->
        @if(!empty($breadcrumbs))
            <nav class="flex justify-center mb-6 px-4">
                <ol class="flex items-center text-xs lg:text-sm max-w-full">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        <li class="flex items-center {{ $loop->last ? 'min-w-0' : 'flex-shrink-0' }}">
                            @if($index > 0)
                                <svg class="w-4 h-4 mx-1 lg:mx-2 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @endif

                            @if($breadcrumb['url'] && !($breadcrumb['is_current'] ?? false))
                                <a href="{{ $breadcrumb['url'] }}" class="text-color-2 hover:text-[#AC2231] transition-colors whitespace-nowrap">
                                    {{ $breadcrumb['text'] }}
                                </a>
                            @else
                                <span class="text-color-2 {{ $loop->last ? 'truncate' : '' }}">
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
            @if(!empty($specifications['estado']) && $specifications['estado'] !== 'N/A')
            <div class="text-left whitespace-nowrap lg:mr-8">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Estado:' : 'Condition:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['estado'] }}</span>
            </div>
            @endif
            @if(!empty($specifications['ano']) && $specifications['ano'] !== 'N/A')
            <div class="text-left whitespace-nowrap lg:mr-8">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Año:' : 'Year:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['ano'] }}</span>
            </div>
            @endif
            @if(!empty($specifications['color']) && $specifications['color'] !== 'N/A')
            <div class="text-left whitespace-nowrap lg:mr-8">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Color:' : 'Color:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['color'] }}</span>
            </div>
            @endif
            @if(!empty($specifications['tamano']) && $specifications['tamano'] !== 'N/A')
            <div class="text-left whitespace-nowrap lg:mr-8">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Tamaño (cm):' : 'Size (cm):' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['tamano'] }}</span>
            </div>
            @endif
            @if(!empty($specifications['material']) && $specifications['material'] !== 'N/A')
            <div class="text-left whitespace-nowrap">
                <span class="text-color-2 font-medium text-base lg:text-lg">{{ app()->getLocale() === 'es' ? 'Material:' : 'Material:' }}</span>
                <span class="font-medium text-base lg:text-lg text-[#AC2231]"> {{ $specifications['material'] }}</span>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[10%_45%_35%] gap-8 mb-16 mt-10 lg:mt-14"
             x-data="{
                 currentIndex: 0,
                 images: @js(collect($productImages)->map(fn($img) => str_starts_with($img['file_path'], 'https://') || str_contains($img['file_path'], 'r2.cloudflarestorage.com') || str_contains($img['file_path'], 'digitaloceanspaces.com') ? $img['file_path'] : asset($img['file_path']))->values()->toArray()),
                 mainImageHeight: 0,
                 touchStartX: 0,
                 touchStartY: 0,
                 swiping: false,
                 updateHeight() {
                     if (this.$refs.mainImage) {
                         this.mainImageHeight = this.$refs.mainImage.offsetHeight;
                     }
                 },
                 goTo(index) {
                     this.currentIndex = index;
                     this.scrollThumbnailIntoView();
                 },
                 next() {
                     this.currentIndex = (this.currentIndex + 1) % this.images.length;
                     this.scrollThumbnailIntoView();
                 },
                 prev() {
                     this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                     this.scrollThumbnailIntoView();
                 },
                 scrollThumbnailIntoView() {
                     this.$nextTick(() => {
                         const container = this.$refs.thumbnailContainer;
                         const thumb = container?.children[this.currentIndex];
                         if (thumb) {
                             thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
                         }
                     });
                 },
                 handleTouchStart(e) {
                     this.touchStartX = e.touches[0].clientX;
                     this.touchStartY = e.touches[0].clientY;
                     this.swiping = false;
                 },
                 handleTouchMove(e) {
                     if (!this.touchStartX) return;
                     const diffX = this.touchStartX - e.touches[0].clientX;
                     const diffY = this.touchStartY - e.touches[0].clientY;
                     if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 10) {
                         this.swiping = true;
                         e.preventDefault();
                     }
                 },
                 handleTouchEnd(e) {
                     if (!this.swiping) return;
                     const diffX = this.touchStartX - e.changedTouches[0].clientX;
                     if (Math.abs(diffX) > 40) {
                         diffX > 0 ? this.next() : this.prev();
                     }
                     this.touchStartX = 0;
                     this.touchStartY = 0;
                     this.swiping = false;
                 }
             }"
             x-init="
                 $nextTick(() => { updateHeight(); });
                 window.addEventListener('resize', () => { updateHeight(); });
                 if ($refs.mainImage) {
                     new ResizeObserver(() => { updateHeight(); }).observe($refs.mainImage);
                 }
             ">
            <!-- Column 1: Thumbnail Carousel -->
            <div class="flex flex-col">
                <div class="flex lg:flex-col gap-3 overflow-x-auto lg:overflow-y-auto lg:overflow-x-visible h-auto lg:h-auto"
                     :style="window.innerWidth >= 1024 ? `height: ${mainImageHeight}px` : ''"
                     x-ref="thumbnailContainer">
                    <template x-for="(src, index) in images" :key="index">
                        <div class="flex-shrink-0 cursor-pointer transition-all duration-200 hover:opacity-75 border-2"
                             :class="currentIndex === index ? 'border-color-2' : 'border-transparent'"
                             @click="goTo(index)">
                            <img :src="src"
                                 :alt="'Product image ' + (index + 1)"
                                 class="w-24 lg:w-full object-contain bg-transparent rounded-lg aspect-square">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Column 2: Main Image Display -->
            <div class="relative">
                <div class="relative bg-transparent aspect-square mx-auto" x-ref="mainImage"
                     @touchstart="handleTouchStart($event)"
                     @touchmove="handleTouchMove($event)"
                     @touchend="handleTouchEnd($event)">
                    <template x-if="images.length > 0">
                        <div class="w-full h-full relative">
                            <template x-for="(src, index) in images" :key="'main-' + index">
                                <img :src="src"
                                     alt="{{ $product->getTranslation('name', app()->getLocale()) }}"
                                     class="absolute inset-0 w-full h-full object-contain transition-opacity duration-200"
                                     :class="currentIndex === index ? 'opacity-100' : 'opacity-0 pointer-events-none'">
                            </template>
                        </div>
                    </template>
                    <template x-if="images.length === 0">
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded-lg">
                            <span class="text-gray-400">{{ app()->getLocale() === 'es' ? 'Sin imagen disponible' : 'No image available' }}</span>
                        </div>
                    </template>

                    <!-- Navigation arrows -->
                    <template x-if="images.length > 1">
                        <div>
                            <button @click="prev()"
                                    class="absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-black bg-opacity-70 text-white rounded-full hover:bg-opacity-90 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button @click="next()"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-black bg-opacity-70 text-white rounded-full hover:bg-opacity-90 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Dot indicators -->
                <template x-if="images.length > 1">
                    <div class="flex justify-center mt-6 space-x-2">
                        <template x-for="(src, index) in images" :key="'dot-' + index">
                            <button @click="goTo(index)"
                                    class="w-3 h-3 rounded-full transition-all"
                                    :class="currentIndex === index ? 'bg-color-2' : 'bg-gray-300'">
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Column 3: Product Information (30%) -->
            <div class="flex flex-col">
                <!-- Price -->
                @if($product->is_sold_out !== true)
                    @php
                        $price = $product->price;
                        $discountedPrice = $product->discounted_price ?? 0;
                        $hasDiscount = $discountedPrice > 0 && $discountedPrice < $price;
                    @endphp

                    @if($hasDiscount)
                        @php
                            $discountPercentage = round((($price - $discountedPrice) / $price) * 100);
                        @endphp
                        <div class="mb-8 lg:mb-10 text-left w-full max-w-md mx-auto lg:max-w-none lg:mx-0 lg:w-auto px-7 md:px-10 lg:px-0 lg:ml-14">
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="text-3xl font-robotoCondensed text-color-3">
                                    € {{ number_format($discountedPrice, 2, ',', '.') }}
                                </div>
                                <div class="text-xl font-robotoCondensed text-gray-500 line-through">
                                    € {{ number_format($price, 2, ',', '.') }}
                                </div>
                                <span class="bg-color-3 text-white text-sm font-bold px-3 py-1 rounded relative" style="top: -1px;">
                                    -{{ $discountPercentage }}%
                                </span>
                            </div>
                            <span class="text-xs font-normal">{{ trans('components/cart.vat-included') }}</span>
                        </div>
                    @else
                        <div class="text-3xl font-robotoCondensed text-[#CA2530] mb-8 lg:mb-10 text-left w-full max-w-md mx-auto lg:max-w-none lg:mx-0 lg:w-auto px-7 md:px-10 lg:px-0 lg:ml-14">
                            € {{ number_format($product->price, 2, ',', '.') }}
                            <span class="text-xs font-normal align-baseline">{{ trans('components/cart.vat-included') }}</span>
                        </div>
                    @endif
                @endif

                <!-- Action Buttons -->
                <div class="mb-8 w-full max-w-md mx-auto lg:mx-0 lg:ml-5 px-7 md:px-10">
                    @livewire('cart.add-to-cart-button', [
                        'productId' => $product->id,
                        'productName' => $product->getTranslation('name', app()->getLocale()),
                        'buttonText' => trans('components/cart.add-to-cart'),
                        'isSoldOut' => $product->is_sold_out || $product->out_of_stock,
                        'isHidden' => $product->is_hidden
                    ])
                </div>

                    <!-- Product Specifications - Mobile only, below add-to-cart button -->
                <div class="lg:hidden grid grid-cols-1 gap-4 mb-8 text-sm w-full max-w-md mx-auto lg:mx-0 px-8 md:px-10">
                    @if(!empty($specifications['estado']) && $specifications['estado'] !== 'N/A')
                    <div class="text-left whitespace-nowrap">
                        <span class="text-color-2 font-medium text-base">{{ app()->getLocale() === 'es' ? 'Estado:' : 'Condition:' }}</span>
                        <span class="font-medium text-base text-[#AC2231]"> {{ $specifications['estado'] }}</span>
                    </div>
                    @endif
                    @if(!empty($specifications['ano']) && $specifications['ano'] !== 'N/A')
                    <div class="text-left whitespace-nowrap">
                        <span class="text-color-2 font-medium text-base">{{ app()->getLocale() === 'es' ? 'Año:' : 'Year:' }}</span>
                        <span class="font-medium text-base text-[#AC2231]"> {{ $specifications['ano'] }}</span>
                    </div>
                    @endif
                    @if(!empty($specifications['color']) && $specifications['color'] !== 'N/A')
                    <div class="text-left whitespace-nowrap">
                        <span class="text-color-2 font-medium text-base">{{ app()->getLocale() === 'es' ? 'Color:' : 'Color:' }}</span>
                        <span class="font-medium text-base text-[#AC2231]"> {{ $specifications['color'] }}</span>
                    </div>
                    @endif
                    @if(!empty($specifications['tamano']) && $specifications['tamano'] !== 'N/A')
                    <div class="text-left whitespace-nowrap">
                        <span class="text-color-2 font-medium text-base">{{ app()->getLocale() === 'es' ? 'Tamaño (cm):' : 'Size (cm):' }}</span>
                        <span class="font-medium text-base text-[#AC2231]"> {{ $specifications['tamano'] }}</span>
                    </div>
                    @endif
                    @if(!empty($specifications['material']) && $specifications['material'] !== 'N/A')
                    <div class="text-left whitespace-nowrap">
                        <span class="text-color-2 font-medium text-base">{{ app()->getLocale() === 'es' ? 'Material:' : 'Material:' }}</span>
                        <span class="font-medium text-base text-[#AC2231]"> {{ $specifications['material'] }}</span>
                    </div>
                    @endif
                </div>

                <!-- Certificado Autenticidad -->
                <div style="background-color: #C12637; padding: 1rem;" class="flex flex-col justify-center mx-auto ml-0 lg:ml-5 w-full mb-8">
                    <span style="color: white;" class="font-medium mb-2 ml-4">{{ __('pages/product-detail.certificate-title') }}</span>
                    <div class="flex ml-4">
                        <img src="{{ asset('images/icons/safe_icon.svg') }}" alt="Safe icon" class="w-10 h-10 mr-2">
                        <span style="color: white;" class="text-sm font-normal">
                            @if(app()->getLocale() === 'es')
                                Este producto ha sido autentificado y está cubierto por nuestra <a href="/es/garantia-vitalicia" style="text-decoration: underline; font-weight: bold; color: white;">garantía de por vida</a>.
                            @else
                                This product has been authenticated and is covered by our <a href="/en/lifetime-guarantee" style="text-decoration: underline; font-weight: bold; color: white;">lifetime warranty</a>.
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="">
                    <p class="text-gray-600 leading-relaxed mx-4 lg:mx-0 lg:ml-10">
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
