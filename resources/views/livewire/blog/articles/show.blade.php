<div class="relative top-8">
    @if($articleExists)
        <img src="{{ asset($articleMainImage) }}" class="w-full h-64">
    @endif
    <div class="flex items-center justify-center absolute top-20 w-full">
        <div class="pt-12 px-32 text-center z-20 bg-red-500 relative top-20 w-3/4">
            @if(!$articleExists)
                <h1 class="font-robotoCondensed font-medium text-6xl mx-auto inline-block">{{$articleNotFoundText}}</h1>
            @else
                <h1 class="font-robotoCondensed font-medium text-6xl mx-auto inline-block">{{$articleTitle}}</h1>
                <div class = "mt-20 text-justify pb-16">{!! $articleBody !!}</div>
            @endif
        </div>
    </div>
</div>