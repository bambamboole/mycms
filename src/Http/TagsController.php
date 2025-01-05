<?php

namespace Bambamboole\MyCms\Http;

use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Post;
use Illuminate\Contracts\View\View;
use Spatie\Tags\Tag;

class TagsController
{
    public function show(string $slug): View
    {
        $tag = Tag::where('slug->en', $slug)->firstOrFail();
        $posts = Post::published()->withAnyTags([$tag])->paginate(5);

        return MyCms::theme()->getTagView($tag, $posts);
    }
}
