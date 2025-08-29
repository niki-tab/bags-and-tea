@extends('layouts.app')

@section('main-tag-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-desktop-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-mobile-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

<meta name="robots" content="noindex, nofollow" />

@section('content')

<div x-data="{ activeTab: 'login' }">
    <div id="auth-tabs" class="bg-[#F6F0ED] py-8 md:py-20">
        <div class="border-[4px] border-[#3A1515] w-4/5 mx-auto">
            <div class="flex border-b border-[#3A1515]">
                <button @click="activeTab = 'login'" :class="{ 'bg-[#3A1515] text-white': activeTab === 'login' }" class="w-1/2 py-4 text-center font-bold">{{ trans('auth.login_title') }}</button>
                <button @click="activeTab = 'register'" :class="{ 'bg-[#3A1515] text-white': activeTab === 'register' }" class="w-1/2 py-4 text-center font-bold">{{ trans('auth.register_title') }}</button>
            </div>
            <div x-show="activeTab === 'login'">
                @livewire('src.auth.frontend.login')
            </div>
            <div x-show="activeTab === 'register'">
                @livewire('src.auth.frontend.register')
            </div>
        </div>
    </div>
</div>

@endsection
