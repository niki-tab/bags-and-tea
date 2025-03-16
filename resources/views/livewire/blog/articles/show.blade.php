<div class="relative">
    <div class="flex items-center justify-center w-full">
        <div class="pt-12 px-8 md:px-32 text-center z-20 bg-white w-full md:w-3/4 h-full pb-[380px]">
            @if(!$articleExists)
                <h1 class="font-robotoCondensed font-medium text-6xl mx-auto inline-block">{{$articleNotFoundText}}</h1>
            @else
                <div class="mb-6">
                    <h1 class="font-robotoCondensed font-medium text-6xl mx-auto inline-block">{{$articleTitle}}</h1>
                    <p class="mt-10 ml-0 pl-0 font-robotoCondensed text-base text-color-6"><a href="*">Autenticidad</a>  &nbsp;&nbsp;-&nbsp;&nbsp;  <a href="*">Moda</a></p>
                </div>
                <img src="{{ asset($articleMainImage) }}" class="h-[520px] w-full border border-color-6">
                <div class = "mt-20 text-justify">{!! $articleBody !!}</div>
            @endif
        </div>
    </div>
</div>
