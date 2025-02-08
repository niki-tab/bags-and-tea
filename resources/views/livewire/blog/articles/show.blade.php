<div class="container mx-auto px-4 py-8">
    @if(!$articleExists)
        <h1>{{$articleNotFoundText}}</h1>
    @else
        <h1>{{$articleTitle}}</h1>
    @endif
</div>