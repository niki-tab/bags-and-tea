<div class="bg-transparent py-4">
    <a href="https://www.trustpilot.com/review/bagsandtea.com" target="_blank">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-center gap-3 md:gap-6">
            <!-- Rating Text -->
            <div class="text-xl md:text-2xl font-medium text-gray-900">
                {{ $ratingText }}
            </div>

            <!-- Star Rating -->
            <div class="flex items-center gap-1">
                @for ($i = 1; $i <= $maxStars; $i++)
                    @php
                        $fillPercentage = 0;
                        if ($i <= floor($ratingScore)) {
                            $fillPercentage = 100;
                        } elseif ($i == ceil($ratingScore)) {
                            // Get decimal part and convert to percentage
                            $decimalPart = $ratingScore - floor($ratingScore);
                            if ($decimalPart > 0) {
                                $fillPercentage = $decimalPart * 100;
                            }
                        }
                    @endphp

                    <div class="relative w-7 h-7 md:w-9 md:h-9 rounded-sm overflow-hidden">
                        @if ($fillPercentage == 100)
                            <!-- Full green background -->
                            <div class="w-full h-full" style="background-color: #00B67A;"></div>
                        @elseif ($fillPercentage == 0)
                            <!-- Full gray background -->
                            <div class="w-full h-full" style="background-color: #DCDCE6;"></div>
                        @else
                            <!-- Partial fill: two divs side by side -->
                            <div class="flex h-full">
                                <div style="width: {{ $fillPercentage }}%; background-color: #00B67A;"></div>
                                <div style="width: {{ 100 - $fillPercentage }}%; background-color: #DCDCE6;"></div>
                            </div>
                        @endif

                        <!-- Trustpilot Star on top -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <img src="{{ asset('images/logo/trustpilot.png') }}" alt="Star" class="w-5 h-5 md:w-6 md:h-6">
                        </div>
                    </div>
                @endfor
                
            </div>

            <!-- Review Count and Trustpilot Logo -->
            <div class="flex items-center gap-2">
                <span class="text-sm md:text-base text-gray-900">
                    {{ number_format($reviewCount) }} {{ __('shared.reviews_on') }}
                </span>
                <img src="{{ asset('images/logo/trustpilot_logo.png') }}" alt="Trustpilot Logo" class="h-8 md:h-10 mb-0.5 ml-1">
            </div>
        </div>
    </div>
    </a>
</div>
