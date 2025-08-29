@extends('layouts.app')

@section('main-tag-class')
bg-background-color-4
@endsection

@section('footer-desktop-class')
bg-background-color-4
@endsection

@section('footer-mobile-class')
bg-background-color-4
@endsection

<meta name="robots" content="noindex, nofollow" />

@section('content')

<div class="w-full">
    <div class="bg-[#F6F0ED] py-8 md:py-20">
        <div class="border-[4px] border-[#3A1515] w-4/5 mx-auto p-8">
            <h2 class="text-center text-2xl font-bold text-[#3A1515] pt-8">{{ trans('my-account.title') }}</h2>
            <p class="text-center">{{ trans('my-account.welcome') }}, {{ Auth::user()->name }}</p>
        </div>
    </div>
</div>

@endsection
