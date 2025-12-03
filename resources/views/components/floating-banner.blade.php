@props([
    'title' => 'Encuentra el bolso que te representa.',
    'titleMobile' => null,
    'buttonText' => 'Ver la colección',
    'buttonTextMobile' => null,
    'buttonLink' => '#',
    'delay' => 10,
    'image' => null,
    'imageMobile' => null
])

<div
    x-data="{
        show: false,
        closed: false,
        init() {
            setTimeout(() => {
                if (!this.closed) {
                    this.show = true;
                }
            }, {{ $delay }} * 1000);
        }
    }"
    x-show="show && !closed"
    x-transition:enter="transition ease-out duration-500 transform"
    x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-300 transform"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    class="fixed bottom-0 left-0 right-0 shadow-2xl"
    style="display: none; z-index: 99999 !important;"
>
    <div class="relative w-full h-36 md:h-52 overflow-hidden flex">
        <!-- Close Button - Top Right Corner -->
        <button
            @click="closed = true; show = false"
            class="absolute top-2 right-2 md:top-4 md:right-4 bg-background-color-3 hover:bg-background-color-5 text-white hover:text-gray-200 transition-colors duration-200 p-2 rounded z-10 shadow-lg"
            aria-label="Close banner"
        >
            <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Left Column: Text Content -->
        <div class="w-3/5 md:w-2/5 bg-gradient-to-r from-background-color-5 to-background-color-3 flex flex-col items-center justify-center px-3 md:px-10 py-4 md:py-6">
            <!-- Mobile Title -->
            <h3 class="md:hidden text-white text-sm font-rosaline text-center mb-2 leading-tight">
                {{ $titleMobile ?? $title }}
            </h3>
            <!-- Desktop Title -->
            <h3 class="hidden md:block text-white text-2xl lg:text-3xl font-rosaline text-center mb-6 leading-tight">
                {{ $title }}
            </h3>

            <a
                href="{{ $buttonLink }}"
                target="_blank"
                rel="noopener noreferrer"
                @click="closed = true; show = false"
                class="bg-color-2 hover:bg-color-4 text-white hover:text-color-2 transition-colors duration-200 font-['Lora'] whitespace-nowrap"
            >
                <!-- Mobile Button Text -->
                <span class="md:hidden px-4 py-1.5 text-xs block">{{ $buttonTextMobile ?? $buttonText }}</span>
                <!-- Desktop Button Text -->
                <span class="hidden md:block px-10 py-3 text-base">{{ $buttonText }}</span>
            </a>
        </div>

        <!-- Right Column: Image -->
        <div class="w-2/5 md:w-3/5 bg-gray-800 relative overflow-hidden">
            @if($image || $imageMobile)
                <!-- Mobile Image -->
                <img
                    src="{{ $imageMobile ?? $image }}"
                    alt="Banner image"
                    class="md:hidden absolute inset-0 w-full h-full object-cover"
                >
                <!-- Desktop Image -->
                <img
                    src="{{ $image }}"
                    alt="Banner image"
                    class="hidden md:block absolute inset-0 w-full h-full object-cover"
                >
            @else
                <div class="absolute inset-0 bg-gradient-to-r from-gray-700 to-gray-800"></div>
            @endif
        </div>
    </div>
</div>
