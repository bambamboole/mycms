<article class="mb-6">
    <header class="mb-4">
        <div class="flex justify-between mb-6">
            <div class="text-grey">{{$post->published_at->toFormattedDayDateString()}}</div>
            <div class="flex text-grey">
                <div class="pr-1">
                    @svg('clock', 'fill-current h-4 w-4')
                </div>
                <div class="font-bold tracking-wide text-grey uppercase -mt-1">
                    {{ $post->readingTime()  }} min read
                </div>
            </div>
        </div>
        <h2 class="font-condensed font-bold text-4xl mb-4"><a class="text-grey-dark"
                                                              href="{{$post->path()}}">{!! $post->title !!}</a>
        </h2>
        <div class="mb-4 pb-6 border-b-2 border-slate">
            @forelse($post->tags as $tag)
                <a href="{{route('tags.show', $tag->slug)}}">
                    <span class="text-sm text-white bg-grey py-1 px-2 mr-2">{{$tag->name}}</span>
                </a>
            @empty
            @endforelse
        </div>
        <div class="py-4 text-xl leading-normal font-serif">
            {!! $post->excerpt !!}
        </div>
        <div class="text-center">
            @if($featuredImage = $post->featured_media)
                <img src="{{$featuredImage}}"/>
            @endif
        </div>
    </header>
</article>
