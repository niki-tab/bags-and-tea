<div class="flex items-center justify-center">
    <div class="px-32 text-center">
        @if(!$articleExists)
            <h1 class="text-6xl mx-auto inline-block">{{$articleNotFoundText}}</h1>
        @else
            <h1 class="text-6xl mx-auto inline-block">{{$articleTitle}}</h1>
            <div class = "mt-20 text-justify">{!! $articleBody !!}</div>
        @endif
    </div>
</div>