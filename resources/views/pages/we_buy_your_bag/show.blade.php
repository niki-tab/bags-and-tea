@extends('layouts.app')

@section('main-tag-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-desktop-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('content')

@php
    $formFields = [
        [
            'label' => trans('pages/we-buy-your-bag.form-name'),
            'placeholder' => trans('pages/we-buy-your-bag.form-name'),
            'type' => 'text',
            'name' => 'name',
        ],
        [
            'label' => trans('pages/we-buy-your-bag.form-last-name'),
            'placeholder' => trans('pages/we-buy-your-bag.form-last-name'),
            'type' => 'text',
            'name' => 'last_name',
        ],
        [
            'label' => trans('pages/we-buy-your-bag.form-email'),
            'placeholder' => trans('pages/we-buy-your-bag.form-email'),
            'type' => 'email',
            'name' => 'email',
        ],
        [
            'label' => trans('pages/we-buy-your-bag.form-phone'),
            'placeholder' => trans('pages/we-buy-your-bag.form-phone'),
            'type' => 'tel',
            'name' => 'phone',
        ],
        [
            'label' => trans('pages/we-buy-your-bag.form-city'),
            'placeholder' => trans('pages/we-buy-your-bag.form-city'),
            'type' => 'text',
            'name' => 'city',
        ],
        [
            'label' => trans('pages/we-buy-your-bag.form-brand'),
            'placeholder' => trans('pages/we-buy-your-bag.form-brand'),
            'type' => 'text',
            'name' => 'brand',
        ],
        [
            'label' => trans('pages/we-buy-your-bag.form-complements'),
            'type' => 'checkbox',
            'name' => 'complements',
            'options' => [
                'option-1' => trans('pages/we-buy-your-bag.form-complements-option-1'),
                'option-2' => trans('pages/we-buy-your-bag.form-complements-option-2'),
            ],
        ],
        [
            'label' => trans('pages/we-buy-your-bag.form-documentation'),
            'type' => 'checkbox',
            'name' => 'documentation',
            'options' => [
                'option-1' => trans('pages/we-buy-your-bag.form-documentation-option-1'),
                'option-2' => trans('pages/we-buy-your-bag.form-documentation-option-2'),
            ],
        ],
        [
            'label' => trans('pages/we-buy-your-bag.form-message'),
            'placeholder' => trans('pages/we-buy-your-bag.form-message'),
            'type' => 'textarea',
            'name' => 'message',
        ],
    ];

    if(request()->route('bagName')){

        $bagName = request()->route('bagName');

        $translationKey = 'pages/we-buy-your-bag.hero_title-' . $bagName;
        $stringSellYourbagTitle = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.hero_title');

        $translationKey = 'pages/we-buy-your-bag.hero_description-' . $bagName;
        $stringSellYourbagDescription = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.hero_description');

        $translationKey = 'pages/we-buy-your-bag.hero_button-' . $bagName;
        $stringSellYourbagButton = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.hero_button');

        $translationKey = 'pages/we-buy-your-bag.brands_title-' . $bagName;
        $stringBrandsTitle = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.brands_title');

        $translationKey = 'pages/we-buy-your-bag.steps_title-' . $bagName;
        $stringStepsTitle = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.steps_title');

        $translationKey = 'pages/we-buy-your-bag.step_1_title-' . $bagName;
        $stringStep1Title = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.step_1_title');

        $translationKey = 'pages/we-buy-your-bag.step_2_title-' . $bagName;
        $stringStep2Title = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.step_2_title');

        $translationKey = 'pages/we-buy-your-bag.step_3_title-' . $bagName;
        $stringStep3Title = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.step_3_title');
            
        $translationKey = 'pages/we-buy-your-bag.step_1_description-' . $bagName;
        $stringStep1Description = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.step_1_description');
            
        $translationKey = 'pages/we-buy-your-bag.step_2_description-' . $bagName;
        $stringStep2Description = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.step_2_description');
            
        $translationKey = 'pages/we-buy-your-bag.step_3_description-' . $bagName;
        $stringStep3Description = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.step_3_description');

        $translationKey = 'pages/we-buy-your-bag.why_bag_question-' . $bagName;
        $stringWhyBagQuestion = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.why_bag_question');
            
        $translationKey = 'pages/we-buy-your-bag.why_bag_answer-' . $bagName;
        $stringWhyBagAnswer = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.why_bag_answer');    
            
        $translationKey = 'pages/we-buy-your-bag.why-bags-and-tea-question-' . $bagName;
        $stringWhyBagsAndTeaQuestion = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.why-bags-and-tea-question'); 
            
        $translationKey = 'pages/we-buy-your-bag.why-bags-and-tea-answer-' . $bagName;
        $stringWhyBagsAndTeaAnswer = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.why-bags-and-tea-answer');    
        
        $translationKey = 'pages/we-buy-your-bag.faq_title-' . $bagName;
        $stringFaqTitle = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.faq_title');

        $iBagName = "-" . $bagName;

        $translationKey = 'pages/we-buy-your-bag.final_section_title-' . $bagName;
        $stringFinalSectionTitle = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.final_section_title');

        $translationKey = 'pages/we-buy-your-bag.final_section_description_1-' . $bagName;
        $stringFinalSectionDescription1 = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.final_section_description_1');

        $translationKey = 'pages/we-buy-your-bag.final_section_description_2-' . $bagName;
        $stringFinalSectionDescription2 = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/we-buy-your-bag.final_section_description_2');   

    }else{
        $stringSellYourbagTitle = trans('pages/we-buy-your-bag.hero_title');
        $stringSellYourbagDescription = trans('pages/we-buy-your-bag.hero_description');
        $stringSellYourbagButton = trans('pages/we-buy-your-bag.hero_button');

        $stringBrandsTitle = trans('pages/we-buy-your-bag.brands_title');

        $stringStepsTitle = trans('pages/we-buy-your-bag.steps_title');
        $stringStep1Title = trans('pages/we-buy-your-bag.step_1_title');
        $stringStep2Title = trans('pages/we-buy-your-bag.step_2_title');
        $stringStep3Title = trans('pages/we-buy-your-bag.step_3_title');
        $stringStep1Description = trans('pages/we-buy-your-bag.step_1_description');
        $stringStep2Description = trans('pages/we-buy-your-bag.step_2_description');
        $stringStep3Description = trans('pages/we-buy-your-bag.step_3_description');

        $stringWhyBagQuestion = trans('pages/we-buy-your-bag.why_bag_question');
        $stringWhyBagAnswer = trans('pages/we-buy-your-bag.why_bag_answer');

        $stringWhyBagsAndTeaQuestion = trans('pages/we-buy-your-bag.why-bags-and-tea-question');
        $stringWhyBagsAndTeaAnswer = trans('pages/we-buy-your-bag.why-bags-and-tea-answer');

        $stringFaqTitle = trans('pages/we-buy-your-bag.faq_title');
        $iBagName = '';

        $stringFinalSectionTitle = trans('pages/we-buy-your-bag.final_section_title');
        $stringFinalSectionDescription1 = trans('pages/we-buy-your-bag.final_section_description_1');
        $stringFinalSectionDescription2 = trans('pages/we-buy-your-bag.final_section_description_2');
    }
@endphp
<div class="w-full relative bg-[#F6F0ED]">
    <div class="flex flex-col md:flex-row h-auto md:h-96">
        <div class="w-full md:w-1/2 bg-[#CB4853] flex items-center justify-center h-full py-12 md:py-0">
            <div class="w-3/4 mx-auto">
                <h1 class="text-4xl md:text-5xl font-['Lovera'] text-white">
                    {{ $stringSellYourbagTitle }}
                </h1>
                <p class="font-mixed text-white mt-6 md:mt-8">
                {{ $stringSellYourbagDescription }}
                </p>
                <button onclick="document.getElementById('sell-your-bag-form').scrollIntoView({behavior: 'smooth'})" class="mt-6 md:mt-8 bg-black text-white px-8 md:px-12 py-2 md:py-3 rounded-full font-medium">
                    {{ $stringSellYourbagButton }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>
                </button>
            </div>


        </div>
        <div class="w-full md:w-1/2 bg-[#DEA3A5] py-12 md:py-0">
            <img src="{{ asset('images/we_buy_your_bag/Bolso_YSL1.svg') }}" 
                alt="Luxury YSL Bag" 
                class="w-2/3 md:w-1/2 mx-auto md:mt-16">
        </div>
    </div>
    <div class="py-8 md:py-16 bg-[#F8F3F0]">
        <h2 class="text-center text-2xl md:text-4xl mb-8 md:mb-14 font-['Lovera']">{{ $stringBrandsTitle }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 max-w-7xl mx-auto mb-4 px-4 md:px-4 lg:px-4 xl:px-0">
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
                <img src="{{ asset('images/we_buy_your_bag/Hermes.svg') }}" alt="Luxury Hermes Bag" 
                    class="w-2/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
                <img src="{{ asset('images/we_buy_your_bag/yves-saint-laurent.svg') }}" alt="Luxury YSL Bag" 
                    class="w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
                <img src="{{ asset('images/we_buy_your_bag/Dior.svg') }}" alt="Luxury Dior Bag" 
                    class="w-2/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
                <img src="{{ asset('images/we_buy_your_bag/Louis-Vuitton.svg') }}" alt="Luxury Louis Vuitton Bag" 
                    class="w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
                <img src="{{ asset('images/we_buy_your_bag/Gucci.svg') }}" alt="Luxury Gucci Bag" 
                    class="w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
                <img src="{{ asset('images/we_buy_your_bag/Prada.svg') }}" alt="Luxury Prada Bag" 
                    class="w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
                <img src="{{ asset('images/we_buy_your_bag/Goyard.svg') }}" alt="Luxury Goyard Bag" 
                    class="w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
                <img src="{{ asset('images/we_buy_your_bag/Chanel.svg') }}" alt="Luxury Chanel Bag" 
                    class="w-2/5">
            </div>
        </div>
    </div>
    <div class="bg-[#C8928A] text-white pb-14 text-center">
        <h2 class="mx-4 relative top-12 text-center text-4xl font-regular font-['Lovera']">{{ $stringStepsTitle }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-8 px-4 md:px-16 py-12 md:py-4 md:h-[500px] mt-0 md:mt-6">
            <!-- First item -->
            <div class="mt-6 text-center md:text-left mx-8 md:mx-0">
            <div class="flex items-end gap-4 mb-8 md:mb-0"> <!-- Changed items-start to items-center -->
                <span class="leading-[0.8] text-[180px] md:text-[245px] text-[#482626] font-['Lovelina'] leading-none">{{ trans('pages/we-buy-your-bag.step_1_number') }}</span>
                <h3 class="pb-6 text-justify text-3xl md:text-4xl text-robotoCondensed font-regular w-3/5 md:w-3/5 mb-5 md:mb-6"> <!-- Removed mt-[40px] md:mt-28 -->
                    {{ $stringStep1Title }}
                </h3>
            </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular mt-4 md:mt-[-60px] pt-0 md:pt-20">
                    {{ $stringStep1Description }}
                </p>
            </div>
            <div class="mt-6 text-center md:text-left mx-8 md:mx-0">
                <div class="flex items-end gap-4 mb-8 md:mb-0">
                    <span class="leading-[0.8] text-[180px] md:text-[245px] text-[#482626] font-['Lovelina'] leading-none">{{ trans('pages/we-buy-your-bag.step_2_number') }}</span>
                    <h3 class="pb-6 text-justify text-3xl md:text-4xl text-robotoCondensed font-regular w-3/5 md:w-3/5 mb-5 md:mb-6">
                        {{ $stringStep2Title }}
                    </h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular mt-4 md:mt-[-60px] pt-0 md:pt-20">
                    {{ $stringStep2Description }}
                </p>
            </div>
            <div class="mt-6 text-center md:text-left mx-8 md:mx-0">
                <div class="flex items-end gap-4 mb-8 md:mb-0">
                    <span class="leading-[0.8] text-[180px] md:text-[245px] text-[#482626] font-['Lovelina'] leading-none">{{ trans('pages/we-buy-your-bag.step_3_number') }}</span>
                    <h3 class="pb-6 text-justify text-3xl md:text-4xl text-robotoCondensed font-regular w-3/5 md:w-3/5 mb-5 md:mb-6">
                        {{ $stringStep3Title }}
                    </h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular mt-4 md:mt-[-60px] pt-0 md:pt-20">
                    {{ $stringStep3Description }}
                </p>
            </div>
        </div>
        <button onclick="document.getElementById('sell-your-bag-form').scrollIntoView({behavior: 'smooth'})" class="bg-black text-white px-12 py-3 rounded-full font-medium mx-auto mt-12 md:mt-0">{{ $stringSellYourbagButton }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;></button>
        
    </div>
    <div class="bg-[#F8F3F0] py-20 hidden lg:block">
        <div class="w-full md:w-full mx-auto bg-[#F8F3F0] relative">
            <div class="absolute bg-[#F8F3F0] p-4 w-2/5 left-52 top-16">
            <h2 class="text-center mx-auto w-1/2 font-['Lovera'] text-4xl text-[#482626]">{{ $stringWhyBagQuestion }}</h2>
                <p class="mx-auto w-full text-[#482626] text-robotoCondensed mt-4">{{ $stringWhyBagAnswer }}</p>
            </div>
            <div class="flex justify-end mr-56"> <!-- Added this container -->
                <img src="{{ asset('images/we_buy_your_bag/group-142.svg') }}" 
                    alt="YSL bag" 
                    class="w-3/5"> <!-- Optional: adjust width as needed -->
            </div>
        </div>
        <div class="w-full md:w-full mx-auto bg-[#F8F3F0] relative mt-32">
            <div class="flex justify-start"> <!-- Added this container -->
                <img src="{{ asset('images/we_buy_your_bag/group-143.svg') }}" 
                    alt="YSL bag" 
                    class="w-3/5"> <!-- Optional: adjust width as needed -->
            </div>
            <div class="absolute bg-[#F8F3F0] p-4 w-2/5 right-52 bottom-32">
                <h2 class="text-center mx-auto w-3/4 font-['Lovera'] text-4xl text-[#482626]">{{ $stringWhyBagsAndTeaQuestion }}</h2>
                <p class="mx-auto w-full text-[#482626] text-robotoCondensed mt-4">{{ $stringWhyBagsAndTeaAnswer }}</p>
            </div>
        </div>
    </div>
    <div class="lg:hidden py-14 bg-[#F8F3F0]">
        <div class="flex flex-col items-center justify-center gap-8">
            <!-- Row 1: Heading -->
            <h2 class="mx-16 text-4xl text-center font-['Lovera'] text-[#482626]">
                {{ $stringWhyBagQuestion }}
            </h2>

            <!-- Row 2: Paragraph -->
            <p class="mx-10 text-center max-w-2xl text-[#482626]">
                {{ $stringWhyBagAnswer }}
            </p>

            <!-- Row 3: Image -->
            <div class="flex justify-center my-8 w-full px-4">
                <img
                    src="{{ asset('images/we_buy_your_bag/group-138.svg') }}" 
                    alt="Description" 
                    class="w-full h-auto">
            </div>
        </div>
        <div class="flex flex-col items-center justify-center gap-8 my-8">
            <!-- Row 1: Heading -->
            <h2 class="mx-16 text-4xl text-center font-['Lovera'] text-[#482626]">
                {{ $stringWhyBagsAndTeaQuestion }}
            </h2>

            <!-- Row 2: Paragraph -->
            <p class="mx-10 text-center max-w-2xl text-[#482626]">
                {{ $stringWhyBagsAndTeaAnswer }}
            </p>

            <!-- Row 3: Image -->
            <div class="flex justify-center w-full">
                <img
                    src="{{ asset('images/we_buy_your_bag/group-139.svg') }}" 
                    alt="Description" 
                    class="w-full h-auto">
            </div>
        </div>
    </div>
        <!-- FAQs Section -->
        <div class="bg-[#3A1515] text-white py-12">
        <h2 class="mx-6 text-center text-4xl font-regular mb-10 md:mb-20 font-['Lovera']">{{ $stringFaqTitle }}</h2>
        <div class="mx-auto space-y-4 pb-10 px-12 md:px-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-x-32 max-w-8xl mx-auto mb-4 mt-12 w-full">
                @for ($i = 1; $i <= 10; $i++)
                    <div class="md:col-span-1">
                        <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer flex items-center justify-between">

                            @if(Lang::has("pages/we-buy-your-bag.faq_{$i}_question{$iBagName}"))
                                <h3 class="inline text-xl w-4/5">{{ trans("pages/we-buy-your-bag.faq_{$i}_question{$iBagName}") }}</h3>
                            @else
                                <h3 class="inline text-xl w-4/5">{{ trans("pages/we-buy-your-bag.faq_{$i}_question") }}</h3>
                            @endif

                            <span class="text-xl mr-4">+</span>
                            </summary>
                            <p class="mt-2">{{ trans("pages/we-buy-your-bag.faq_{$i}_answer") }}</p>
                        </details>  
                    </div>
                @endfor
            </div>
            
            <!-- Repeat for other FAQs -->
        </div>
    </div>

    <!-- Sell Your Bag Section -->
    <div class="hidden lg:block">
        <div class="relative">
        <!-- Top half background -->
        <div class="absolute top-0 left-0 right-0 h-3/4 bg-[#DEA3A5]"></div>
        <!-- Bottom half background -->
        <div class="absolute bottom-0 left-0 right-0 h-1/4 bg-[#F6F0ED]"></div>
        
        <!-- Content -->
        <div class="relative z-10 h-[850px]">
            <div class="">
                <div class="flex flex-col md:flex-row items-start">
                    <!-- Image Section -->
                    <div class="w-full md:w-4/5 pt-8">
                        <img src="{{ asset('images/we_buy_your_bag/lv-bag-we-buy-your-bags.svg') }}" 
                            alt="Louis Vuitton Bag" 
                            class="w-full">
                    </div>
                    
                    <!-- Text Section -->
                    <div class="w-full pb-42 md:pb-0">
                        <div class="w-[90%] md:w-[70%] bg-white p-8 md:p-12 bg-[#F6F0ED] border-l-[12px] border-b-[12px] border-[#BE6F62] md:relative md:left-32 mx-auto md:mx-0">
                            <h2 class="text-3xl md:text-4xl font-['Lovera'] text-[#3A1515] mb-6">
                                {{ $stringFinalSectionTitle }}
                            </h2>
                            <p class="text-[#3A1515] mb-6">
                                {{ $stringFinalSectionDescription1 }}
                            </p>
                            <p class="text-[#3A1515] mb-6">
                                {{ $stringFinalSectionDescription2 }}
                            </p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col lg:hidden bg-[#DEA3A5] gap-8"> <!-- Changed from grid to flex -->
        <!-- First section -->
        <div>
            <div class="mt-16 w-[90%] md:w-[70%] bg-white p-8 md:p-12 bg-[#F6F0ED] border-l-[12px] border-b-[12px] border-[#BE6F62] mx-auto">
                <h2 class="text-3xl md:text-4xl font-['Lovera'] text-[#3A1515] mb-6">
                    {{ $stringFinalSectionTitle }}
                </h2>
                <p class="text-[#3A1515] mb-6">
                    {{ $stringFinalSectionDescription1 }}
                </p>
                <p class="text-[#3A1515] mb-6">
                    {{ $stringFinalSectionDescription2 }}
                </p>
            </div>
        </div>
        
        <!-- Second section -->
        <div>
            <img src="{{ asset('images/we_buy_your_bag/group-145.svg') }}" 
                alt="Louis Vuitton Bag" 
                class="w-full">
        </div>
    </div>
    <div id="sell-your-bag-form" class="bg-[#F6F0ED] py-4 md:py-0 w-full md:w-2/3 mx-auto border-[16px] border-[#F6F0ED] relative z-10 md:-mt-80">
        <div class="border-[4px] border-[#3A1515]">
            @livewire('crm/forms/show', ['formTitle' => trans('pages/we-buy-your-bag.form-title'), 'formFields' => $formFields])
        </div>
    </div>
</div>
@endsection

@metadata