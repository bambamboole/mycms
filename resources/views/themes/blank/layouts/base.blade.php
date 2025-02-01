@php
    /**
    * @var \Bambamboole\MyCms\Models\BasePostType $post
    */
    $renderer = app(\Bambamboole\MyCms\Blocks\BlockRenderer::class)
@endphp
@foreach($post->blocks ?? [] as $block)
    {!! $renderer->render($block, $post)  !!}
@endforeach
