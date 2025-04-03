@extends('layouts.app')
<meta name="robots" content="noindex, nofollow" />
@section('content')
<div class="flex items-center justify-center mt-10">
    <div class="text-left font-robotoCondensed text-xl w-5/6 space-y-4 my-4">

        <h2 class="font-bold text-4xl">{{ __('pages/policy.title') }}</h2>
        <div class="py-1"></div>
        <p class="">{{ __('pages/policy.intro') }}</p>

        <h3 class="font-bold">{{ __('pages/policy.section-1-title') }}</h3>
        <p>{{ __('pages/policy.section-1-text-1') }}</p>
        <ul class="list-disc list-inside space-y-1">
            <li>{{ __('pages/policy.section-1-item-1') }}</li>
            <li>{{ __('pages/policy.section-1-item-2') }}</li>
            <li>{{ __('pages/policy.section-1-item-3') }}</li>
            <li>{{ __('pages/policy.section-1-item-4') }}</li>
        </ul>

        <h3 class="font-bold">{{ __('pages/policy.section-2-title') }}</h3>
        <p>{{ __('pages/policy.section-2-text-1') }}</p>
        <ul class="list-disc list-inside space-y-1">
            <li><strong>{{ __('pages/policy.section-2-item-1-label') }}:</strong> {{ __('pages/policy.section-2-item-1-text') }}</li>
            <li><strong>{{ __('pages/policy.section-2-item-2-label') }}:</strong> {{ __('pages/policy.section-2-item-2-text') }}</li>
            <li><strong>{{ __('pages/policy.section-2-item-3-label') }}:</strong> {{ __('pages/policy.section-2-item-3-text') }}</li>
        </ul>

        <h3 class="font-bold">{{ __('pages/policy.section-3-title') }}</h3>
        <p>{{ __('pages/policy.section-3-text') }}</p>

        <h3 class="font-bold">{{ __('pages/policy.section-4-title') }}</h3>
        <p>{{ __('pages/policy.section-4-text') }}</p>

        <h3 class="font-bold">{{ __('pages/policy.section-5-title') }}</h3>
        <p>{{ __('pages/policy.section-5-text-1') }}</p>
        <ul class="list-disc list-inside space-y-1">
            <li><strong>{{ __('pages/policy.section-5-item-1-label') }}:</strong> {{ __('pages/policy.section-5-item-1-text') }}</li>
            <li><strong>{{ __('pages/policy.section-5-item-2-label') }}:</strong> {{ __('pages/policy.section-5-item-2-text') }}</li>
            <li><strong>{{ __('pages/policy.section-5-item-3-label') }}:</strong> {{ __('pages/policy.section-5-item-3-text') }}</li>
            <li><strong>{{ __('pages/policy.section-5-item-4-label') }}:</strong> {{ __('pages/policy.section-5-item-4-text') }}</li>
            <li><strong>{{ __('pages/policy.section-5-item-5-label') }}:</strong> {{ __('pages/policy.section-5-item-5-text') }}</li>
        </ul>
        <p>{{ __('pages/policy.section-5-footer') }}</p>
        <h3 class="font-bold">{{ __('pages/policy.section-6-title') }}</h3>
        <p>{{ __('pages/policy.section-6-text') }}</p>

        <h3 class="font-bold">{{ __('pages/policy.section-7-title') }}</h3>
        <p>{{ __('pages/policy.section-7-text') }}</p>
        <p>{{ __('pages/policy.section-7-footer') }}</p>

    </div>
</div>
</div>

@endsection