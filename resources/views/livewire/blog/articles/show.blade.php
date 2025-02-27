<div class="relative top-8">
    @if($articleExists)
        <img src="{{ asset($articleMainImage) }}" class="w-full h-64 absolute">
    @endif
    <div class="flex items-center justify-center w-full">
        <div class="pt-12 px-32 text-center z-20 bg-white relative top-44 w-3/4 h-full pb-[380px]">
            @if(!$articleExists)
                <h1 class="font-robotoCondensed font-medium text-6xl mx-auto inline-block">{{$articleNotFoundText}}</h1>
            @else
                <h1 class="font-robotoCondensed font-medium text-6xl mx-auto inline-block">{{$articleTitle}}</h1>
                <img src="{{ asset($articleMainImage) }}" class="h-[520px] w-full">
                <div class = "mt-20 text-justify">{!! $articleBody !!}</div>
            @endif
        </div>
    </div>
</div>
