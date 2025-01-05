<?php

namespace Bambamboole\MyCms\Http;

use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Post;
use Spatie\Tags\Tag;

class TagsController
{
    public function show(string $slug)
    {
        $tag = Tag::where('slug->en', $slug)->firstOrFail();
        $posts = Post::published()->withAnyTags([$tag])->paginate(5);

        return MyCms::theme()->getTagView($tag, $posts);
    }
}
