@php
    $productSlug = $product->getTranslation('slug', app()->getLocale());
    $productDetailRoute = app()->getLocale() === 'es' 
        ? route('product.show.es', ['locale' => 'es', 'productSlug' => $productSlug])
        : route('product.show.en', ['locale' => 'en', 'productSlug' => $productSlug]);
@endphp

<a href="{{ $productDetailRoute }}" class="bg-white overflow-hidden hover:shadow-lg transition-shadow duration-300 block">
    {{-- Product Image Carousel --}}
    @php
        $productImages = $product->media ? $product->media->where('file_type', 'image')->map(function($media) {
            // Check if it's a cloud storage URL (R2, DigitalOcean Spaces) or local storage path
            if (str_starts_with($media->file_path, 'https://') ||
                str_contains($media->file_path, 'r2.cloudflarestorage.com') ||
                str_contains($media->file_path, 'digitaloceanspaces.com')) {
                return $media->file_path; // Use cloud storage URL directly
            } else {
                return asset($media->file_path); // Use asset() for local storage
            }
        })->toArray() : [];
        $totalImages = count($productImages);
    @endphp
    <div class="relative bg-transparent h-64 overflow-hidden" x-data="{
        currentImage: 0,
        images: @js($productImages),
        totalImages: @js($totalImages),
        touchStartX: 0,
        touchStartY: 0,
        swiping: false,
        init() {
            this.images.forEach(src => { new Image().src = src; });
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
            e.preventDefault();
            e.stopPropagation();
            const diffX = this.touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diffX) > 40) {
                this.currentImage = diffX > 0
                    ? (this.currentImage < this.totalImages - 1 ? this.currentImage + 1 : 0)
                    : (this.currentImage > 0 ? this.currentImage - 1 : this.totalImages - 1);
            }
            this.touchStartX = 0;
            this.touchStartY = 0;
            this.swiping = false;
        }
    }"
    @touchstart="handleTouchStart($event)"
    @touchmove="handleTouchMove($event)"
    @touchend="handleTouchEnd($event)">
        {{-- Main Image Display --}}
        <template x-if="totalImages > 0">
            <img :src="images[currentImage]" :alt="{{ json_encode($product->name) }}" class="w-full h-full object-contain">
        </template>
        
        {{-- No Image Placeholder --}}
        <template x-if="totalImages === 0">
            <div class="w-full h-full flex items-center justify-center text-gray-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </template>
        
        {{-- Navigation Arrows --}}
        <template x-if="totalImages > 1">
            <div>
                {{-- Previous Arrow --}}
                <button 
                    @click.stop.prevent="currentImage = currentImage > 0 ? currentImage - 1 : totalImages - 1"
                    class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-[#F8F3F0] hover:bg-[#F8F3F0]/80 text-color-2 p-2 rounded-full transition-all duration-200 z-10"
                    title="Previous image"
                    onclick="event.stopPropagation(); event.preventDefault(); return false;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                {{-- Next Arrow --}}
                <button 
                    @click.stop.prevent="currentImage = currentImage < totalImages - 1 ? currentImage + 1 : 0"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#F8F3F0] hover:bg-[#F8F3F0]/80 text-color-2 p-2 rounded-full transition-all duration-200 z-10"
                    title="Next image"
                    onclick="event.stopPropagation(); event.preventDefault(); return false;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </template>
        
        {{-- Image Indicators (dots) --}}
        <template x-if="totalImages > 1">
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-1">
                <template x-for="(image, index) in images" :key="index">
                    <button 
                        @click.stop.prevent="currentImage = index"
                        :class="currentImage === index ? 'bg-white' : 'bg-white bg-opacity-50'"
                        class="w-2 h-2 rounded-full transition-all duration-200 hover:bg-opacity-80"
                        onclick="event.stopPropagation(); event.preventDefault(); return false;">
                    </button>
                </template>
            </div>
        </template>
        
        {{-- Diagonal Sold Out Banner --}}
        @if($product->is_sold_out === true)
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 translate-y-8 -rotate-[15deg] bg-[#C12637] bg-opacity-75 w-[120%] h-10 flex items-center justify-center shadow-[0_3px_4px_0px_rgba(0,0,0,0.25)]">
                    <span class="text-white text-sm font-robotoCondensed font-medium uppercase tracking-wide">
                        @if(app()->getLocale() === 'es')
                            Agotado
                        @else
                            Sold Out
                        @endif
                    </span>
                </div>
            </div>
        @endif
    </div>

    {{-- Product Info --}}
    <div class="p-4">
        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">
            @if($product->brand)
                {{ $product->brand->getTranslation('name', app()->getLocale()) }}
            @else
                {{ __('MARCA') }}
            @endif
        </div>
        <h3 class="text-sm font-medium text-gray-900 mb-2">
            {{ $product->getTranslation('name', app()->getLocale()) ?? __('Modelo') }}
        </h3>
        @if($product->is_sold_out !== true)
            @php
                $price = $product->price ?? 450;
                $discountedPrice = $product->discounted_price ?? 0;
                $hasDiscount = $discountedPrice > 0 && $discountedPrice < $price;

                // Format original price
                $formattedPrice = ($price == floor($price))
                    ? number_format($price, 0, ',', '.')
                    : number_format($price, 2, ',', '.');

                // Format discounted price
                $formattedDiscountedPrice = ($discountedPrice == floor($discountedPrice))
                    ? number_format($discountedPrice, 0, ',', '.')
                    : number_format($discountedPrice, 2, ',', '.');
            @endphp

            @if($hasDiscount)
                @php
                    $discountPercentage = round((($price - $discountedPrice) / $price) * 100);
                @endphp
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="text-lg font-bold text-color-3">
                        {{ $formattedDiscountedPrice }}€
                    </div>
                    <div class="text-sm text-gray-500 line-through">
                        {{ $formattedPrice }}€
                    </div>
                    <span class="bg-color-3 text-white text-xs font-bold px-2 py-1 rounded relative" style="top: -1px;">
                        -{{ $discountPercentage }}%
                    </span>
                </div>
            @else
                <div class="text-lg font-bold text-gray-900">
                    {{ $formattedPrice }}€
                </div>
            @endif
        @endif
    </div>
</a>
