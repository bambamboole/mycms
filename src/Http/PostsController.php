<?php

namespace Bambamboole\MyCms\Http;

use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Post;
use Illuminate\Support\Facades\Context;

class PostsController
{
    public function index()
    {
        $posts = Post::query()
            ->published()
            ->with('tags')
            ->orderBy('published_at')
            ->paginate(5);

        return MyCms::theme()->getPostIndexView($posts);
    }

    public function show($slug)
    {
        $post = Post::query()
            ->published()
            ->with('tags')
            ->where('slug', $slug)
            ->firstOrFail();
        Context::add('current_post', $post);

        return MyCms::theme()->getPostView($post);
    }
}
