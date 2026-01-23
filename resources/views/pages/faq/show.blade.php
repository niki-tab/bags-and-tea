@extends('layouts.app')

@section('main-tag-class')
bg-color-4
@endsection

@section('footer-desktop-class')
bg-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-mobile-class')
bg-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

<meta name="robots" content="noindex, nofollow" />

@section('content')

<!-- Top white space -->
<div class="bg-color-4 h-16 md:h-24"></div>

<!-- FAQs Section -->
<div class="bg-[#3A1515] text-white py-12">
    <h1 class="mx-6 text-center text-4xl font-regular mb-10 md:mb-20 font-['Lovera']">{{ trans('pages/faq.faq_title') }}</h1>
    <div class="mx-auto space-y-4 pb-10 px-12 md:px-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-x-32 max-w-8xl mx-auto mb-4 mt-12 w-full">
            @for ($i = 1; $i <= 10; $i++)
                @if(Lang::has("pages/faq.faq_{$i}_question"))
                    <div class="md:col-span-1">
                        <details class="pb-4 md:pb-6 border-b border-white">
                            <summary class="cursor-pointer flex items-center justify-between">
                                <h3 class="inline text-xl w-4/5">{{ trans("pages/faq.faq_{$i}_question") }}</h3>
                                <span class="text-xl mr-4">+</span>
                            </summary>
                            <p class="mt-2">{!! trans("pages/faq.faq_{$i}_answer") !!}</p>
                        </details>
                    </div>
                @endif
            @endfor
        </div>
    </div>
</div>

<!-- Bottom white space -->
<div class="bg-color-4 h-16 md:h-24"></div>

@endsection

@metadata
