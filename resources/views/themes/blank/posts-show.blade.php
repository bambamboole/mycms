@extends('mycms::themes.blank.layout')
@php
    /**
    * @var \Bambamboole\MyCms\Models\Post $post
    */
@endphp

@section('seo')
    {!! seo()->for($post) !!}
@endsection

@section('content')
    {!! $post->contentAsHtml() !!}
@endsection
