<div class="p-8">
                    
                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form wire:submit.prevent="login" class="space-y-6">
                        <div>
                            <label for="email" class="block text-theme-color-2 font-medium mb-2">
                                {{ trans('auth.email') }}
                            </label>
                            <input 
                                wire:model="email" 
                                type="email" 
                                id="email" 
                                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-button-color-1 focus:border-transparent transition-colors"
                                placeholder="{{ trans('auth.email') }}"
                            >
                            @error('email') 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-theme-color-2 font-medium mb-2">
                                {{ trans('auth.password') }}
                            </label>
                            <input 
                                wire:model="password" 
                                type="password" 
                                id="password" 
                                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-button-color-1 focus:border-transparent transition-colors"
                                placeholder="{{ trans('auth.password') }}"
                            >
                            @error('password') 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button 
                                type="submit" 
                                class="w-full bg-color-2 text-white border-2 border-color-2 py-3 px-6 text-lg font-semibold hover:bg-background-color-3 hover:text-white hover:border-background-color-3 transition-colors duration-200 font-robotoCondensed"
                            >
                                {{ trans('auth.login_button') }}
                            </button>
                        </div>


                    </form>
                </div>
