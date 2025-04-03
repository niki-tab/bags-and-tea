@extends('layouts.app')
<meta name="robots" content="noindex, nofollow" />
@section('content')
<div class="flex items-center justify-center mt-10">
    <div class="text-left font-robotoCondensed text-xl w-2/3 space-y-4">
        <h2 class="font-bold">{{ __('pages/cookies.title') }}</h2>

        <h3 class="font-bold">{{ __('pages/cookies.section-1-title') }}</h3>
        <p>{{ __('pages/cookies.section-1-text') }}</p>

        <h3 class="font-bold">{{ __('pages/cookies.section-2-title') }}</h3>
        <p>{{ __('pages/cookies.section-2-text-1') }}</p>
        <ul class="list-disc list-inside space-y-2">
            <li><strong>{{ __('pages/cookies.section-2-essential') }}</strong></li>
            <li><strong>{{ __('pages/cookies.section-2-performance') }}</strong></li>
            <li><strong>{{ __('pages/cookies.section-2-marketing') }}</strong></li>
            <li><strong>{{ __('pages/cookies.section-2-session') }}</strong></li>
        </ul>

        <h3 class="font-bold">{{ __('pages/cookies.section-3-title') }}</h3>
        <p>{{ __('pages/cookies.section-3-text') }}</p>

        <h3 class="font-bold">{{ __('pages/cookies.section-4-title') }}</h3>
        <p>{{ __('pages/cookies.section-4-text') }}</p>

        <h3 class="font-bold">{{ __('pages/cookies.section-5-title') }}</h3>
        <p>{{ __('pages/cookies.section-5-text') }}</p>

        <p>{{ __('pages/cookies.section-contact') }}</p>
    </div>
</div>

@endsection