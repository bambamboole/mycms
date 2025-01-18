@extends('mycms::themes.blank.layout')
@php
    /**
    * @var \Bambamboole\MyCms\Models\Page $page
    */
    $renderer = app(\Bambamboole\MyCms\Blocks\BlockRenderer::class)
@endphp

@section('seo')
    {!! seo()->for($page) !!}
@endsection

@section('content')
    @foreach($page->blocks ?? [] as $block)
        {!! $renderer->render($block)  !!}
    @endforeach
    {!! $page->contentAsHtml() !!}
@endsection
