<div class="flex items-center justify-center mb-2 md:mb-4">
    <div class="mt-4 md:mt-12 px-8 md:px-32 text-center">
        <h1 class="mx-auto inline-block mb-8 md:mb-12 text-4xl md:text-5xl font-['Lovera']" style="color: #482626;">{{trans('components/article-all-show.page-title')}}</h1>
        @if(!$allArticles)
            <h1 class="text-6xl mx-auto inline-block">{{$articlesNotFoundText}}</h1>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($allArticles as $article)
                <a href="{{ route(app()->getLocale() === 'es' ? 'article.show.es' : 'article.show.en', ['articleSlug' => $article["slug"], 'locale' => app()->getLocale()]) }}" 
                class="block bg-white shadow-lg overflow-hidden hover:shadow-xl transition">
                    <img src="{{ $article["main_image"] ?? 'https://source.unsplash.com/400x250/?blog' }}" 
                        alt="{{ $article["title"] }}" 
                        class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h2 class="text-xl font-robotoCondensed text-color-2 mb-2">{{ strip_tags($article["title"]) }}</h2>
                        <p class="text-[#4D5562] font-robotoCondensed text-sm mb-4">{{ Str::limit(strip_tags($article["body"]), 100) }}</p>
                        <span class="text-color-2 font-robotoCondensed font-light">{{ trans('components/article-all-show.label-read-more') }}&nbsp;→</span>
                    </div>
                </a>
            @endforeach
        </div>
        @endif
    </div>
</div>