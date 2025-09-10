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

<div 
    x-data="{ activeTab: 'login' }" 
    class="container mx-auto p-4"
>
    <div class="bg-background-color-4 py-8 md:py-20">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-lg overflow-hidden">
                <div class="flex">
                    <button 
                        @click="activeTab = 'login'" 
                        :class="{ 
                            'bg-background-color-3 text-white': activeTab === 'login',
                            'bg-white text-gray-700 hover:bg-gray-200': activeTab !== 'login'
                        }" 
                        class="w-1/2 py-4 text-center font-semibold transition-colors font-robotoCondensed text-lg"
                    >
                        {{ trans('auth.login_title') }}
                    </button>
                    <button 
                        @click="activeTab = 'register'" 
                        :class="{ 
                            'bg-background-color-3 text-white': activeTab === 'register',
                            'bg-white text-gray-700 hover:bg-gray-200': activeTab !== 'register'
                        }" 
                        class="w-1/2 py-4 text-center font-semibold transition-colors font-robotoCondensed text-lg"
                    >
                        {{ trans('auth.register_title') }}
                    </button>
                </div>
                
                <div class="relative">
                    <div x-show="activeTab === 'login'" x-transition>
                        @livewire('src.auth.frontend.login')
                    </div>
                    <div x-show="activeTab === 'register'" x-transition>
                        @livewire('src.auth.frontend.register')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
