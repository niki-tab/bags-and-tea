<div class="w-full bg-[#F6F0ED] px-6 md:px-20 py-12 md:py-20">
    <h2 class="text-3xl md:text-4xl font-['Lovera'] text-[#3A1515] mx-auto md:mx-0 text-center md:text-left">
        {{ $formTitle }}
    </h2>
    <form wire:submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-x-10 md:gap-y-4 mt-8 md:mt-10"> <!-- Added grid container -->
            @foreach ($formFields as $field)
                @if ($field['type'] === 'textarea')
        </div>
        <textarea 
                    wire:model="formData.{{ trans($field['name']) }}"
                    name="{{ trans($field['name']) }}"
                    class="mt-6 font-robotoCondensed h-36 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                    placeholder="{{ trans($field['placeholder']) }}"></textarea>
                    @error('formData.' . trans($field['name']))
                        <span class="text-[#B92334] text-xs relative top-[-12px]">{{ $message }}</span>
                    @enderror
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-x-10 md:gap-y-4 mt-8 md:mt-10"> <!-- Added grid container -->
                @else
                    <div class="">
                    @if ($field['type'] === 'text')
                        <input type="text" 
                               wire:model="formData.{{ trans($field['name']) }}"
                               name="{{ trans($field['name']) }}" 
                               class="font-robotoCondensed h-8 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                               placeholder="{{ trans($field['placeholder']) }}">
                               @error('formData.' . trans($field['name']))
                                <span class="text-[#B92334] text-xs relative top-[-12px]">{{ $message }}</span>
                               @enderror
                    @elseif ($field['type'] === 'email')
                        <input type="email" 
                               wire:model="formData.{{ trans($field['name']) }}"
                               name="{{ trans($field['name']) }}" 
                               class="font-robotoCondensed h-8 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                               placeholder="{{ trans($field['placeholder']) }}">
                               @error('formData.' . trans($field['name']))
                                <span class="text-[#B92334] text-xs relative top-[-12px]">{{ $message }}</span>
                               @enderror
                    @elseif ($field['type'] === 'tel')
                        <input type="tel" 
                               wire:model="formData.{{ trans($field['name']) }}"
                               name="{{ trans($field['name']) }}" 
                               class="font-robotoCondensed h-8 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                               placeholder="{{ trans($field['placeholder']) }}">    
                               @error('formData.' . trans($field['name']))
                                <span class="text-[#B92334] text-xs relative top-[-12px]">{{ $message }}</span>
                               @enderror
                    @elseif ($field['type'] === 'radio')
                        <label for="{{ trans($field['name']) }}" class="font-robotoCondensed text-color-2 ml-4 block mb-2">{{ trans($field['label']) }}</label>
                        @foreach ($field['options'] as $option)
                            <div class="flex items-center mb-2 ml-12">
                                <input type="radio" 
                                    wire:model="formData.{{ trans($field['name']) }}"
                                    name="{{ trans($field['name']) }}" 
                                    value="{{ trans($option) }}"
                                    class=""
                                    >
                                <label for="{{ trans($option) }}" class="font-robotoCondensed text-color-2 ml-4">{{ trans($option) }}</label>
                            </div>
                        @endforeach
                        @error('formData.' . trans($field['name']))
                                <span class="text-[#B92334] text-xs relative top-[0px]">{{ $message }}</span>
                        @enderror
                    @elseif ($field['type'] === 'file')
                        <div class="mb-4">
                            <label for="{{ trans($field['name']) }}" class="font-bold font-robotoCondensed text-color-2 ml-4 block mb-2">{{ trans($field['label']) }}</label>
                            <input type="file"
                                wire:model="formData.{{ $field['name'] }}"
                                class="font-robotoCondensed ml-8"
                                accept="image/*"
                                multiple>
                            <div wire:loading wire:target="formData.{{ $field['name'] }}">
                                Uploading...
                            </div>
                            @if ($field['image'])
                                <img src="{{ asset($field['image']) }}" 
                                    alt="{{ trans($field['label']) }}" 
                                    class="w-1/2 h-1/2 mt-8 {{ !empty($formData[$field['name']]) ? 'border-2 border-green-500 p-2' : '' }}">
                            @endif
                        </div>
                        @error('formData.' . trans($field['name']))
                                <span class="text-[#B92334] text-xs relative top-[0px]">{{ $message }}</span>
                        @enderror
                    @endif
                    </div>
                @endif
            @endforeach
        </div>
        @if ($isTermsAndConditions)
        <p class="text-color-2 text-xs mt-4">
            <input type="checkbox" wire:model="formData.termsAndConditions" name="termsAndConditions" id="termsAndConditions">
            <label for="termsAndConditions" class="ml-2 text-color-2 text-sm font-robotoCondensed font-regular">{{ trans('components/contact-form.label-terms-and-conditions-1') }} <a href="{{ route(app()->getLocale() === 'es' ? 'privacy.show.es' : 'privacy.show.en', ['locale' => app()->getLocale()]) }}" class="text-[#B92334] font-robotoCondensed font-bold underline">{{ trans('components/contact-form.label-terms-and-conditions-2') }}</a></label>
            @error('formData.termsAndConditions')
                <span class="text-[#B92334] text-xs relative left-[12px]">{{ $message }}</span>
            @enderror
        </p>
        @endif
        @if ($isReceiveComercialInformation)
        <p class="text-color-2 text-xs mt-4">
            <input type="checkbox" wire:model="formData.receiveComercialInformation" name="receiveComercialInformation" id="receiveComercialInformation">
            <label for="receiveComercialInformation" class="ml-2 text-color-2 text-sm font-robotoCondensed font-regular">{{ trans('components/contact-form.label-receive-comercial-information') }}</label>
            @error('formData.receiveComercialInformation')
                <span class="text-[#B92334] text-xs relative left-[12px]">{{ $message }}</span>
            @enderror
        </p>
        @endif
        <div class="flex justify-center mt-6">
            <button type="submit" class="mt-4 bg-color-2 text-white px-8 py-2 rounded-full font-robotoCondensed">{{ trans($formButtonText) }}</button>
        </div>
    </form>
</div>
