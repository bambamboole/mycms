@extends('mycms::themes.blank.layout')
@php
    /**
    * @var \Spatie\Tags\Tag $tag
    * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $posts
    */
@endphp

@section('content')
    <h1>{{$tag->name}}</h1>
    <ul>
        @foreach($posts as $post)
            <li>
                <a href="{{$post->path()}}">{!! $post->title !!}</a>
            </li>
        @endforeach
    </ul>
    {{$posts->links()}}

@endsection
