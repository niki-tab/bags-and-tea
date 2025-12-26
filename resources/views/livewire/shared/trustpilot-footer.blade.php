<a href="https://www.trustpilot.com/review/bagsandtea.com" target="_blank" class="flex items-center gap-3">
    <!-- Star Rating -->
    <div class="flex items-center gap-1">
        @for ($i = 1; $i <= $maxStars; $i++)
            @php
                $fillPercentage = 0;
                if ($i <= floor($ratingScore)) {
                    $fillPercentage = 100;
                } elseif ($i == ceil($ratingScore)) {
                    $decimalPart = $ratingScore - floor($ratingScore);
                    if ($decimalPart > 0) {
                        $fillPercentage = $decimalPart * 100;
                    }
                }
            @endphp

            <div class="relative w-6 h-6 rounded-sm overflow-hidden">
                @if ($fillPercentage == 100)
                    <div class="w-full h-full" style="background-color: #00B67A;"></div>
                @elseif ($fillPercentage == 0)
                    <div class="w-full h-full" style="background-color: #DCDCE6;"></div>
                @else
                    <div class="flex h-full">
                        <div style="width: {{ $fillPercentage }}%; background-color: #00B67A;"></div>
                        <div style="width: {{ 100 - $fillPercentage }}%; background-color: #DCDCE6;"></div>
                    </div>
                @endif

                <div class="absolute inset-0 flex items-center justify-center">
                    <img src="{{ asset('images/logo/trustpilot.png') }}" alt="Star" class="w-4 h-4">
                </div>
            </div>
        @endfor
    </div>

    <!-- Trustpilot Logo -->
    <img src="{{ asset('images/logo/trustpilot_logo.png') }}" alt="Trustpilot" class="h-10">
</a>
