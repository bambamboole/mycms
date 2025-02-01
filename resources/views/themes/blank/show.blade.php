@extends('mycms::themes.blank.wrapper')
@php
    /**
    * @var \Bambamboole\MyCms\Models\BasePostType $post
    */
@endphp

@section('seo')
    {!! seo()->for($post) !!}
@endsection

@section('content')
    <x-dynamic-component component="{{ $post->layout ?? 'base-layout' }}" :post="$post" />
@endsection
