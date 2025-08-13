<div class="relative article-content">
    <div class="flex items-center justify-center w-full">
        <div class="pt-12 px-8 md:px-16 text-center z-20 bg-white w-full md:w-3/4 h-full pb-[380px]">
            @if(!$articleExists)
                <h1 class="font-robotoCondensed font-medium text-6xl mx-auto inline-block">{{$articleNotFoundText}}</h1>
            @else
                <div class="mb-6 inline-block text-center">
                    <h1 class="font-robotoCondensed font-medium text-6xl mx-auto inline-block text-[#3A3F42] [letter-spacing:-2px]">{{$articleTitle}}</h1>
                    
                    <!-- Blog Category Breadcrumbs -->
                    @if(!empty($breadcrumbs) && count($breadcrumbs) > 1)
                        <div class="mt-10 ml-0 pl-0 font-robotoCondensed text-base text-color-6">
                            @php
                                $categoryBreadcrumbs = array_slice($breadcrumbs, 1, -1); // Skip Blog and Article title
                            @endphp
                            
                            @if(count($categoryBreadcrumbs) > 0)
                                @foreach($categoryBreadcrumbs as $index => $breadcrumb)
                                    @if($breadcrumb['url'])
                                        <a href="{{ $breadcrumb['url'] }}" class="hover:text-[#AC2231] transition-colors">{{ $breadcrumb['text'] }}</a>
                                    @else
                                        <span>{{ $breadcrumb['text'] }}</span>
                                    @endif
                                    
                                    @if($index < count($categoryBreadcrumbs) - 1)
                                        &nbsp;&nbsp;-&nbsp;&nbsp;
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
                <img src="{{ asset($articleMainImage) }}" class="h-[300px] sm:h-[400px] md:h-[520px] lg:h-[520px] w-full object-cover mt-8">
                <div id="article-body" class = "mt-14 md:mt-20 text-justify px-2 md:px-20">{!! $articleBody !!}</div>
            @endif
        </div>
    </div>
</div>
