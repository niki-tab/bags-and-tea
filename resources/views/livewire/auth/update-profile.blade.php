<div>
    @if (session()->has('profile_success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            x-transition.opacity.duration.500ms
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" 
            role="alert"
        >
            <span class="block sm:inline">{{ session('profile_success') }}</span>
            <button @click="show = false" class="absolute top-2 right-2 text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    <form wire:submit.prevent="updateProfile" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-theme-color-2 font-medium mb-2">
                    {{ trans('my-account.name') }}
                </label>
                <input 
                    wire:model="name"
                    type="text" 
                    id="name" 
                    class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-button-color-1 focus:border-transparent transition-colors"
                >
                @error('name') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-theme-color-2 font-medium mb-2">
                    {{ trans('my-account.email') }}
                </label>
                <input 
                    wire:model="email"
                    type="email" 
                    id="email" 
                    class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-button-color-1 focus:border-transparent transition-colors"
                >
                @error('email') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                @enderror
            </div>
        </div>
        
        <div class="pt-4">
            <button 
                type="submit" 
                class="bg-color-2 text-white border-2 border-color-2 py-3 px-6 font-semibold hover:bg-background-color-3 hover:border-background-color-3 transition-colors duration-200 font-robotoCondensed"
            >
                {{ trans('my-account.update_profile') }}
            </button>
        </div>
    </form>
</div>