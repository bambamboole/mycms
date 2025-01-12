@extends('mycms::themes.blank.layout')
@php
    /**
    * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $posts
    */
@endphp

@section('content')

    <ul>
        @foreach($posts as $post)
            <li>
                <a href="{{$post->path()}}">{!! $post->title !!}</a>
            </li>
        @endforeach
    </ul>
    {{$posts->links()}}

@endsection
