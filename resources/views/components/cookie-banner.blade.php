<div 
    x-data="cookieConsent()" 
    x-show="show" 
    x-cloak
    class="fixed inset-0 z-50 flex md:items-end md:justify-center items-center justify-center bg-black/50"
>
    <!-- Versión desktop -->
    <div class="hidden md:flex w-full max-w-6xl bg-[#482727] text-white px-8 py-5 shadow-lg mx-auto my-12 flex-col md:flex-row items-center md:items-stretch justify-between gap-6 md:gap-0 rounded-none">
        <p class="text-xs md:text-sm flex-1 flex items-center md:justify-start justify-center text-center md:text-left pr-0 md:pr-8">
            {{ trans('components/cookie-banner.text_desktop') }}
        </p>
        <div class="flex flex-row flex-nowrap gap-2 md:gap-3 items-center md:ml-0 ml-0">
            <button 
                @click="personalize()" 
                class="border border-white px-6 py-2 h-10 text-xs md:text-sm hover:bg-white hover:text-[#482727] transition flex items-center justify-center min-w-[120px]"
            >
                {{ trans('components/cookie-banner.personalize') }} <span class="ml-1">▼</span>
            </button>
            <button 
                @click="reject()" 
                class="border border-white px-6 py-2 h-10 text-xs md:text-sm hover:bg-white hover:text-[#482727] transition min-w-[100px]"
            >
                {{ trans('components/cookie-banner.reject') }}
            </button>
            <button 
                @click="accept()" 
                class="bg-white text-[#482727] px-6 py-2 h-10 text-xs md:text-sm hover:opacity-90 transition min-w-[100px]"
            >
                {{ trans('components/cookie-banner.accept') }}
            </button>
        </div>
    </div>
    <!-- Versión móvil -->
    <div class="md:hidden w-full flex justify-center items-center">
        <div class="bg-[#482727] text-white w-[90%] max-w-sm rounded-lg shadow-lg flex flex-col items-stretch px-5 pt-6 pb-4">
            <div class="mb-2">
                <h2 class="text-lg font-semibold mb-2">{{ trans('components/cookie-banner.title') }}</h2>
                <p class="text-xs text-left">{{ trans('components/cookie-banner.text') }}</p>
            </div>
            <div class="border-t border-white/40 my-4"></div>
            <div class="flex flex-col gap-2">
                <button 
                    @click="accept()" 
                    class="bg-white text-[#482727] w-full py-2 rounded font-medium text-sm hover:opacity-90 transition"
                >
                    {{ trans('components/cookie-banner.accept-all') }}
                </button>
                <button 
                    @click="personalize()" 
                    class="border border-white text-white w-full py-2 rounded font-medium text-sm hover:bg-white hover:text-[#482727] transition"
                >
                    {{ trans('components/cookie-banner.personalize') }}
                </button>
                <button 
                    @click="reject()" 
                    class="border border-white text-white w-full py-2 rounded font-medium text-sm hover:bg-white hover:text-[#482727] transition"
                >
                    {{ trans('components/cookie-banner.reject') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de personalización -->
    <div 
        x-show="showModal" 
        x-cloak
        class="fixed inset-0 z-60 flex items-center justify-center bg-black/70"
        @click.self="closeModal()"
    >
        <div class="bg-white rounded-lg shadow-xl max-w-md w-[90%] max-h-[80vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">{{ trans('components/cookie-banner.modal_title') }}</h2>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mt-2">{{ trans('components/cookie-banner.modal_description') }}</p>
            </div>

            <div class="px-6 py-4 space-y-4">
                <!-- Cookies Necesarias -->
                <div class="border-b border-gray-100 pb-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-medium text-gray-800">{{ trans('components/cookie-banner.necessary_title') }}</h3>
                        <div class="bg-gray-300 rounded-full w-12 h-6 flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-600">ON</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">{{ trans('components/cookie-banner.necessary_description') }}</p>
                </div>

                <!-- Cookies de Análisis -->
                <div class="border-b border-gray-100 pb-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-medium text-gray-800">{{ trans('components/cookie-banner.analytics_title') }}</h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="preferences.analytics" class="sr-only peer">
                            <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#482727] peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </div>
                    <p class="text-sm text-gray-600">{{ trans('components/cookie-banner.analytics_description') }}</p>
                </div>

                <!-- Cookies de Marketing -->
                <div class="border-b border-gray-100 pb-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-medium text-gray-800">{{ trans('components/cookie-banner.marketing_title') }}</h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="preferences.marketing" class="sr-only peer">
                            <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#482727] peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </div>
                    <p class="text-sm text-gray-600">{{ trans('components/cookie-banner.marketing_description') }}</p>
                </div>

                <!-- Cookies Funcionales -->
                <div class="pb-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-medium text-gray-800">{{ trans('components/cookie-banner.functional_title') }}</h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="preferences.functional" class="sr-only peer">
                            <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#482727] peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </div>
                    <p class="text-sm text-gray-600">{{ trans('components/cookie-banner.functional_description') }}</p>
                </div>
            </div>

            <div class="sticky bottom-0 bg-gray-50 px-6 py-4 flex gap-3">
                <button @click="savePreferences()" class="flex-1 bg-[#482727] text-white px-4 py-2 rounded hover:bg-[#3a1f1f] transition">
                    {{ trans('components/cookie-banner.save_preferences') }}
                </button>
                <button @click="closeModal()" class="px-4 py-2 border border-gray-300 rounded text-gray-600 hover:bg-gray-100 transition">
                    {{ trans('components/cookie-banner.close') }}
                </button>
            </div>
        </div>
    </div>
</div>
