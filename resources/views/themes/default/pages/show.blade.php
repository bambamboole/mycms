@extends('mycms::themes.default.layouts.app')

@section('title',  $page->title)

@section('seo')
    {!! seo()->for($page) !!}
@endsection

@section('content')
    <main class="py-16">
        {!! $page->contentAsHtml() !!}
    </main>
@endsection
