@extends('mycms::themes.blank.layout')
@php
    /**
    * @var \Bambamboole\MyCms\Models\Page $page
    */
@endphp

@section('seo')
    {!! seo()->for($page) !!}
@endsection

@section('content')
    {!! $page->contentAsHtml() !!}
@endsection
