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

<div class="bg-background-color-4">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-theme-color-2 font-robotoCondensed mb-2">{{ trans('my-account.title') }}</h1>
            <p class="text-gray-600">{{ trans('my-account.welcome') }}, {{ Auth::user()->name }}</p>
        </div>

        <!-- Main Content with Sidebar -->
        <div class="flex flex-col lg:flex-row gap-8" x-data="{ activeSection: 'orders' }">
            <!-- Sidebar Menu -->
            <div class="lg:w-1/4">
                <div class="bg-white shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-theme-color-2 font-robotoCondensed mb-4">{{ trans('my-account.menu') }}</h3>
                    <nav class="space-y-2 mb-6">
                        <button 
                            @click="activeSection = 'orders'" 
                            :class="{ 
                                'bg-color-2 text-white': activeSection === 'orders',
                                'text-gray-700 hover:bg-background-color-4': activeSection !== 'orders'
                            }"
                            class="w-full text-left px-4 py-3 font-medium transition-colors font-robotoCondensed"
                        >
                            <i class="fas fa-shopping-bag mr-3"></i>
                            {{ trans('my-account.orders') }}
                        </button>
                        
                        <button 
                            @click="activeSection = 'settings'" 
                            :class="{ 
                                'bg-color-2 text-white': activeSection === 'settings',
                                'text-gray-700 hover:bg-background-color-4': activeSection !== 'settings'
                            }"
                            class="w-full text-left px-4 py-3 font-medium transition-colors font-robotoCondensed"
                        >
                            <i class="fas fa-cog mr-3"></i>
                            {{ trans('my-account.settings') }}
                        </button>
                        
                        <!-- Future menu items can be added here -->
                        <!-- 
                        <button 
                            @click="activeSection = 'profile'" 
                            :class="{ 
                                'bg-color-2 text-white': activeSection === 'profile',
                                'text-gray-700 hover:bg-background-color-4': activeSection !== 'profile'
                            }"
                            class="w-full text-left px-4 py-3 font-medium transition-colors font-robotoCondensed"
                        >
                            <i class="fas fa-user mr-3"></i>
                            {{ trans('my-account.profile') }}
                        </button>
                        -->
                    </nav>

                    <!-- Logout Button -->
                    <div class="border-t border-gray-200 pt-4">
                        @livewire('src.auth.frontend.logout')
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:w-3/4">
                <div class="bg-white shadow-lg">
                    <!-- Orders Section -->
                    <div x-show="activeSection === 'orders'" class="p-6">
                        <div class="border-b border-gray-200 pb-4 mb-6">
                            <h2 class="text-2xl font-bold text-theme-color-2 font-robotoCondensed">{{ trans('my-account.orders') }}</h2>
                            <p class="text-gray-600 mt-1">{{ trans('my-account.orders_description') }}</p>
                        </div>

                        <!-- Orders Content -->
                        <div class="space-y-4">
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM8 15a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ trans('my-account.no_orders') }}</h3>
                                <p class="text-gray-500 mb-6">{{ trans('my-account.no_orders_description') }}</p>
                                <a href="{{ route('shop.show.' . app()->getLocale(), ['locale' => app()->getLocale()]) }}" class="inline-block bg-color-2 text-white px-6 py-3 font-semibold hover:bg-theme-color-2 transition-colors font-robotoCondensed">
                                    {{ trans('my-account.start_shopping') }}
                                </a>
                            </div>

                            <!-- Future: Order items will be displayed here when orders exist -->
                            <!-- 
                            <div class="border border-gray-200 p-4">
                                Order item template
                            </div>
                            -->
                        </div>
                    </div>

                    <!-- Settings Section -->
                    <div x-show="activeSection === 'settings'" class="p-6">
                        <div class="border-b border-gray-200 pb-4 mb-6">
                            <h2 class="text-2xl font-bold text-theme-color-2 font-robotoCondensed">{{ trans('my-account.settings') }}</h2>
                            <p class="text-gray-600 mt-1">{{ trans('my-account.settings_description') }}</p>
                        </div>

                        <!-- Settings Content -->
                        <div class="space-y-8">
                            <!-- Profile Settings -->
                            <div class="p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-theme-color-2 font-robotoCondensed mb-4">
                                    <i class="fas fa-user mr-2"></i>
                                    {{ trans('my-account.profile_settings') }}
                                </h3>
                                
                                @livewire('src.auth.frontend.update-profile')
                            </div>

                            <!-- Password Settings -->
                            <div class="p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-theme-color-2 font-robotoCondensed mb-4">
                                    <i class="fas fa-lock mr-2"></i>
                                    {{ trans('my-account.change_password') }}
                                </h3>
                                
                                @livewire('src.auth.frontend.change-password')
                            </div>
                        </div>
                    </div>

                    <!-- Future sections can be added here -->
                    <!-- 
                    <div x-show="activeSection === 'profile'" class="p-6">
                        Profile content
                    </div>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
