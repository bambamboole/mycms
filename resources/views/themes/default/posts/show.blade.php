@extends('mycms::themes.default.layouts.app')

@section('title',  $post->title)
@section('seo')
    <meta property="og:title" content="{{ $post->title }} | christlieb.eu"/>
    <meta property="og:description" content="{{ $post->excerpt }}"/>

    <meta property="article:published_time" content="{{ optional($post->published_at)->toIso8601String() }}"/>
    <meta property="og:updated_time" content="{{ $post->updated_at->toIso8601String() }}"/>

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:description" content="{{ $post->excerpt }}"/>
    <meta name="twitter:title" content="{{ $post->title }} | christlieb.eu"/>
    <meta name="twitter:site" content="@bambamboole1"/>
    <meta property="og:image" content="https://christlieb.eu/images/logo.png">
    <meta name="twitter:creator" content="@bambamboole1"/>
@endsection

@section('content')

    <main class="py-3">
        <article>
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
                <h1 class="text-grey-dark font-condensed font-bold text-4xl mb-4">{!! $post->title !!}</h1>
                @if($featuredImage = $post->getFirstMedia('featured_image'))
                    <div class="article-image flex justify-center">
                        {{$featuredImage}}
                    </div>
                @endif
                <div class="mb-4 pt-6 pb-6 border-b-2 border-slate">
                    @forelse($post->tags as $tag)
                        <a href="{{route('tags.show', $tag->slug)}}">
                            <span class="text-sm text-white bg-grey py-1 px-2 mr-2">{{$tag->name}}</span>
                        </a>
                    @empty
                    @endforelse
                </div>
                <div class="py-4 text-xl leading-normal font-serif">
                    {!! $post->contentAsHtml() !!}
                </div>
            </header>
        </article>
    </main>


@endsection
