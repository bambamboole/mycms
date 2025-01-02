@extends('mycms::themes.default.layouts.app')
@php
    Illuminate\Pagination\Paginator::defaultView('mycms::themes.default.partials.pagination');
@endphp
@section('title',  'Blog')
@section('content')

    <main class="py-3">
        @foreach($posts as $post)
            @include('mycms::themes.default.posts.partials.excerpt')
        @endforeach

        {{$posts->links()}}
    </main>



@endsection
