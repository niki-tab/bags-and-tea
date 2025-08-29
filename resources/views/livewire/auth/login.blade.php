<div>
    <div id="login-form" class="bg-[#F6F0ED] py-8 md:py-20">
        <div class="border-[4px] border-[#3A1515] w-4/5 mx-auto">
            <h2 class="text-center text-2xl font-bold text-[#3A1515] pt-8">{{ trans('auth.login_title') }}</h2>
            <form wire:submit.prevent="login">
                <div class="p-8">
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
                    <div class="flex items-center justify-between">
                        <button type="submit" class="w-full bg-[#3A1515] text-white py-2 px-4 rounded-md hover:bg-opacity-80">
                            {{ trans('auth.login_button') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
