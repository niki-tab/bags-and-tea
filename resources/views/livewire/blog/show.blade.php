<div class="flex items-center justify-center">
    <div class="px-32 text-center">
        <h1 class="text-6xl mx-auto inline-block">{{trans('components/article-all-show.page-title')}}</h1>
        @if(!$allArticles)
            <h1 class="text-6xl mx-auto inline-block">{{$articlesNotFoundText}}</h1>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
        </div>
        @endif
    </div>
</div>