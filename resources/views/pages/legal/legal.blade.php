@extends('layouts.app')
<meta name="robots" content="noindex, nofollow" />
@section('content')
<div class="flex items-center justify-center mt-10">
    <div class="text-left font-robotoCondensed text-xl w-2/3 space-y-4 my-4">

        <h2 class="font-bold text-4xl">{{ __('pages/legal.title') }}</h2>
        <div class="py-1"></div>
        <h3 class="font-bold">{{ __('pages/legal.section-1-title') }}</h3>
        <p>{{ __('pages/legal.section-1-text') }}</p>
        <ul class="list-disc list-inside space-y-1">
            <li>{{ __('pages/legal.section-1-name') }}</li>
            <li>{{ __('pages/legal.section-1-nif') }}</li>
            <li>{{ __('pages/legal.section-1-address') }}</li>
            <li>{{ __('pages/legal.section-1-email') }}</li>
        </ul>

        <h3 class="font-bold">{{ __('pages/legal.section-2-title') }}</h3>
        <p>{{ __('pages/legal.section-2-text') }}</p>

        <h3 class="font-bold">{{ __('pages/legal.section-3-title') }}</h3>
        <p>{{ __('pages/legal.section-3-text-1') }}</p>
        <p>{{ __('pages/legal.section-3-text-2') }}</p>

        <p>{{ __('pages/legal.contact-text') }}</p>
    </div>
</div>


@endsection