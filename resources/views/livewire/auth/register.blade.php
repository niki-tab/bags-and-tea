<div>
    <div class="w-full md:block hidden">
        <img 
            src="{{ asset('images/home/image54.png') }}" 
            alt="Bolso Bags and Tea" 
            class="w-full object-contain block mx-auto">
    </div>


    <div class="w-full h-60 md:hidden">
        <img 
            src="{{ asset('images/home/image54.png') }}" 
            alt="Bolso Bags and Tea" 
            class="w-full h-full object-cover mx-auto">
    </div>
    <div id="register-form" class="bg-[#F6F0ED] py-8 md:py-20">
        <div class="border-[4px] border-[#3A1515] w-4/5 mx-auto">
            <h2 class="text-center text-2xl font-bold text-[#3A1515] pt-8">{{ trans('auth.register_title') }}</h2>
            <form wire:submit.prevent="register">
                <div class="p-8">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ trans('auth.name') }}</label>
                        <input wire:model="name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ trans('auth.email') }}</label>
                        <input wire:model="email" type="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">{{ trans('auth.password') }}</label>
                        <input wire:model="password" type="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ trans('auth.password_confirmation') }}</label>
                        <input wire:model="password_confirmation" type="password" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="w-full bg-[#3A1515] text-white py-2 px-4 rounded-md hover:bg-opacity-80">
                            {{ trans('auth.register_button') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
