<?php

namespace Bambamboole\MyCms\Http;

use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Post;
use Illuminate\Contracts\View\View;

class PostsController
{
    public function index(): View
    {
        $posts = Post::query()
            ->published()
            ->with('tags')
            ->orderBy('published_at')
            ->paginate(5);

        return MyCms::theme()->getPostIndexView($posts);
    }

    public function show($slug): View
    {
        $post = Post::query()
            ->published()
            ->with('tags')
            ->where('slug', $slug)
            ->firstOrFail();

        return MyCms::theme()->getPostView($post);
    }
}
