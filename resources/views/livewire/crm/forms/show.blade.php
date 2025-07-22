<div class="w-full bg-[#F6F0ED] px-6 md:px-20 py-12 md:py-20" id="form-container-{{ $formIdentifier }}">
    <h2 class="text-3xl md:text-4xl font-['Lovera'] text-[#3A1515] mx-auto md:mx-0 text-center md:text-left">
        {{ $formTitle }}
    </h2>
    @if ($showSuccessMessage)
        <div class="text-center">
            <p id="form-success-message-{{ $formIdentifier }}" class="text-[#3A1515] font-robotoCondensed text-xl my-12">
                {{ trans('components/form-show.success-message') }}
            </p>
        </div>
    @else
        <form wire:submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-x-10 md:gap-y-4 mt-8 md:mt-10"> <!-- Added grid container -->
                @foreach ($formFields as $field)
                    @if ($field['type'] === 'textarea')
            </div>
            <div class="relative">
                <textarea 
                        wire:model="formData.{{ trans($field['name']) }}"
                        name="{{ trans($field['name']) }}"
                        class="mt-6 font-robotoCondensed h-36 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                        placeholder="{{ trans($field['placeholder']) }}">
                </textarea>
                @if ($field['required'])
                    <span class="text-color-3 font-robotoCondensed font-light absolute top-[10px] right-2 md:right-0">{{ trans('components/form-show.label-required') }}</span>
                @endif
            </div>    
            @error('formData.' . trans($field['name']))
                <span class="text-[#B92334] text-xs relative top-[-12px] form-error" id="error-{{ $formIdentifier }}-{{ trans($field['name']) }}">{{ $message }}</span>
            @enderror
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-x-10 md:gap-y-4 mt-8 md:mt-10"> <!-- Added grid container -->
                    @elseif ($field['type'] === 'section-title')
                    </div>
                    <p class="block font-robotoCondensed text-color-2 text-xl md:ml-3 ml-0 w-full font-regular text-center md:text-left">{{ trans($field['label']) }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-x-10 md:gap-y-4 mt-8 md:mt-10"> <!-- Added grid container -->
                    @else
                        <div class="relative">
                        @if ($field['type'] === 'text')
                            <input type="text" 
                                wire:model="formData.{{ trans($field['name']) }}"
                                name="{{ trans($field['name']) }}" 
                                class="font-robotoCondensed h-8 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                                placeholder="{{ trans($field['placeholder']) }}">
                                @if ($field['required'])
                                    <span class="text-color-3 font-robotoCondensed font-light absolute top-[-8px] right-2 md:right-0">{{ trans('components/form-show.label-required') }}</span>
                                @endif
                                @error('formData.' . trans($field['name']))
                                    <span class="text-[#B92334] text-xs relative top-[-12px] form-error" id="error-{{ $formIdentifier }}-{{ trans($field['name']) }}">{{ $message }}</span>
                                @enderror
                        @elseif ($field['type'] === 'email')
                            <input type="email" 
                                wire:model="formData.{{ trans($field['name']) }}"
                                name="{{ trans($field['name']) }}" 
                                class="font-robotoCondensed h-8 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                                placeholder="{{ trans($field['placeholder']) }}">
                                @if ($field['required'])
                                    <span class="text-color-3 font-robotoCondensed font-light absolute top-[-8px] right-2 md:right-0">{{ trans('components/form-show.label-required') }}</span>
                                @endif
                                @error('formData.' . trans($field['name']))
                                    <span class="text-[#B92334] text-xs relative top-[-12px] form-error" id="error-{{ $formIdentifier }}-{{ trans($field['name']) }}">{{ $message }}</span>
                                @enderror
                        @elseif ($field['type'] === 'tel')
                            <input type="tel" 
                                wire:model="formData.{{ trans($field['name']) }}"
                                name="{{ trans($field['name']) }}" 
                                class="font-robotoCondensed h-8 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                                placeholder="{{ trans($field['placeholder']) }}"
                                pattern="[+]?[0-9\s\-\(\)]{7,20}"
                                title="Ingrese un número de teléfono válido (solo números, espacios, guiones y paréntesis)"
                                oninput="this.value = this.value.replace(/[^+0-9\s\-\(\)]/g, '')"
                                maxlength="20">
                                @if ($field['required'])
                                    <span class="text-color-3 font-robotoCondensed font-light absolute top-[-8px] right-2 md:right-0">{{ trans('components/form-show.label-required') }}</span>
                                @endif    
                                @error('formData.' . trans($field['name']))
                                    <span class="text-[#B92334] text-xs relative top-[-12px] form-error" id="error-{{ $formIdentifier }}-{{ trans($field['name']) }}">{{ $message }}</span>
                                @enderror
                        @elseif ($field['type'] === 'radio')
                            <div class="inline-flex items-center mb-2 text-center md:text-left w-full md:w-auto justify-center md:justify-start">
                                <label for="{{ trans($field['name']) }}" class="font-regular font-robotoCondensed text-color-2 ml-4 md:ml-4 block mb-2 whitespace-nowrap">{{ trans($field['label']) }}</label>
                                @if ($field['required'])
                                    <span class="text-color-3 ml-1 mb-3 font-robotoCondensed font-light whitespace-nowrap">{{ trans('components/form-show.label-required') }}</span>
                                @else
                                    <span class="text-color-2 ml-2 mb-2 font-robotoCondensed font-light whitespace-nowrap">{{ trans('components/form-show.label-optional') }}</span>
                                @endif
                            </div> 
                            @foreach ($field['options'] as $option)
                                <div class="flex items-center mb-2 ml-0 md:ml-12 justify-center md:justify-start">
                                    <div class="flex items-center w-20 md:w-auto justify-start">
                                        <input type="radio" 
                                            wire:model="formData.{{ trans($field['name']) }}"
                                            name="{{ trans($field['name']) }}" 
                                            value="{{ trans($option) }}"
                                            class="flex-shrink-0"
                                        >
                                        <label for="{{ trans($option) }}" class="font-robotoCondensed text-color-2 ml-4 whitespace-nowrap">
                                            {{ trans($option) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                            @error('formData.' . trans($field['name']))
                                    <span class="text-[#B92334] text-xs relative top-[0px] form-error" id="error-{{ $formIdentifier }}-{{ trans($field['name']) }}">{{ $message }}</span>
                            @enderror
                    @elseif ($field['type'] === 'file')
                        <div class="mb-4">
                            <!-- Fixed label container -->
                            <div class="flex flex-wrap items-center justify-center md:justify-start mb-2 text-center md:text-left">
                                <label for="{{ trans($field['name']) }}" class="font-bold font-robotoCondensed text-color-2 mb-1 md:mb-0 mr-2">{{ trans($field['label']) }}</label>
                                @if ($field['required'])
                                    <span class="text-color-3 font-robotoCondensed font-light text-sm">{{ trans('components/form-show.label-required') }}</span>
                                @else
                                    <span class="text-color-2 font-robotoCondensed font-light text-sm">{{ trans('components/form-show.label-optional') }}</span>
                                @endif
                            </div>
                            
                            <!-- File input with better mobile styling -->
                            <div class="mb-4 flex justify-center md:justify-start">
                                <input type="file"
                                    wire:model="files.{{ $field['name'] }}"
                                    class="font-robotoCondensed text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold hover:file:bg-background-color-3 file:transition"
                                    accept="image/*"
                                    multiple>
                            </div>
                            
                            <div wire:loading wire:target="files.{{ $field['name'] }}" class="text-sm text-gray-600 mb-2">
                                Uploading...
                            </div>
                            
                            @if ($field['image'])
                                <!-- Fixed image container -->
                                <div class="mt-4"> <!-- Consistent top margin -->
                                    <img src="{{ asset($field['image']) }}" 
                                        alt="{{ trans($field['label']) }}" 
                                        class="mx-auto md:mx-0 w-full max-w-xs md:w-1/2 md:h-1/2 object-contain {{ isset($files[$field['name']]) && $files[$field['name']] && count($files[$field['name']]) > 0 ? 'border-2 border-[#A2DEA2] p-2' : '' }}">
                                </div>
                            @endif
                        </div>
                        @error('files.' . trans($field['name']))
                            <span class="text-[#B92334] text-xs form-error" id="error-{{ $formIdentifier }}-files-{{ trans($field['name']) }}">{{ $message }}</span>
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
                    <span class="text-[#B92334] text-xs relative left-[12px] form-error" id="error-{{ $formIdentifier }}-termsAndConditions">{{ $message }}</span>
                @enderror
            </p>
            @endif
            <p class="text-color-2 text-xs mt-4">
                <input type="checkbox" wire:model="formData.receiveComercialInformation" name="receiveComercialInformation" id="receiveComercialInformation">
                <label for="receiveComercialInformation" class="ml-2 text-color-2 text-sm font-robotoCondensed font-regular">{{ trans('components/contact-form.label-receive-comercial-information') }}</label>
            </p>

            <div class="flex justify-center mt-6">
                <button type="submit" class="mt-4 bg-color-2 text-white px-8 py-2 rounded-full font-robotoCondensed">{{ trans($formButtonText) }}</button>
            </div>
        </form>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('scrollToForm', (event) => {
        setTimeout(() => {
            const successMessage = document.getElementById('form-success-message-' + event.formIdentifier);
            if (successMessage) {
                successMessage.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }
        }, 100);
    });

    Livewire.on('scrollToFirstError', (event) => {
        setTimeout(() => {
            const firstError = document.querySelector('.form-error');
            if (firstError) {
                firstError.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                // Optional: Add a visual highlight to the error
                firstError.style.backgroundColor = '#ffe6e6';
                setTimeout(() => {
                    firstError.style.backgroundColor = '';
                }, 2000);
            }
        }, 100);
    });
});
</script>
