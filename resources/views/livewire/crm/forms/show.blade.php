<div class="w-full bg-[#F6F0ED] px-6 md:px-20 py-12 md:py-20">
    <h2 class="text-3xl md:text-4xl font-['Lovera'] text-[#3A1515] mx-auto md:mx-0 text-center md:text-left">
        {{ $formTitle }}
    </h2>
    <l>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-x-10 md:gap-y-4 mt-8 md:mt-10"> <!-- Added grid container -->
            @foreach ($formFields as $field)
                @if ($field['type'] === 'textarea')
        </div>
        <textarea 
                    name="{{ $field['name'] }}"
                    class="mt-6 font-robotoCondensed h-36 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                    placeholder="{{ $field['placeholder'] }}"></textarea>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-x-10 md:gap-y-4 mt-8 md:mt-10"> <!-- Added grid container -->
                @else
                    <div class="">
                    @if ($field['type'] === 'text')
                        <input type="text" 
                               name="{{ $field['name'] }}" 
                               class="font-robotoCondensed h-8 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                               placeholder="{{ $field['placeholder'] }}">
                    @elseif ($field['type'] === 'email')
                        <input type="email" 
                               name="{{ $field['name'] }}" 
                               class="font-robotoCondensed h-8 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                               placeholder="{{ $field['placeholder'] }}">
                    @elseif ($field['type'] === 'tel')
                        <input type="tel" 
                               name="{{ $field['name'] }}" 
                               class="font-robotoCondensed h-8 w-full mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0"
                               placeholder="{{ $field['placeholder'] }}">    
                    @elseif ($field['type'] === 'checkbox')
                        <label for="{{ $field['name'] }}" class="font-robotoCondensed text-color-2 ml-4 block mb-2">{{ $field['label'] }}</label>
                        @foreach ($field['options'] as $option)
                            <div class="flex items-center mb-2 ml-12"> <!-- Added container with spacing -->
                                <input type="checkbox" 
                                    name="{{ $option }}" 
                                    value="{{ $option }}"
                                    class=""
                                    >
                                <label for="{{ $option }}" class="font-robotoCondensed text-color-2 ml-4">{{ $option }}</label>
                            </div>
                        @endforeach
                    @endif
                    </div>
                @endif
            @endforeach
        </div>
    </form>
</div>
