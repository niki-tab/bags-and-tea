<div class="flex items-center justify-center">
    <div class="px-32 text-center">
        <h1 class="text-6xl mx-auto inline-block">{{trans('components/article-all-show.page-title')}}</h1>
        @if(!$allArticles)
            <h1 class="text-6xl mx-auto inline-block">{{$articlesNotFoundText}}</h1>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($allArticles as $article)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <img src="{{ $article->main_image ?? 'https://source.unsplash.com/400x250/?blog' }}" 
                        alt="{{ $article->title }}" 
                        class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-2">{{ $article->title }}</h2>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit(strip_tags($article->body), 100) }}</p>
                        <a href="{{ route(app()->getLocale() === 'es' ? 'article.show.es' : 'article.show.en', ['articleSlug' => $article->slug, 'locale' => app()->getLocale()]) }}" 
                        class="text-blue-600 font-semibold hover:underline">
                            Read more →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>