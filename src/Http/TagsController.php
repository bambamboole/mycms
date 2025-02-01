<?php

namespace Bambamboole\MyCms\Http;

use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Post;
use Illuminate\Support\Facades\Context;
use Spatie\Tags\Tag;

class TagsController
{
    public function show(string $slug)
    {
        $tag = Tag::where('slug->en', $slug)->firstOrFail();
        Context::add('current_tag', $tag);
        $posts = Post::published()->withAnyTags([$tag])->paginate(5);

        return MyCms::theme()->renderIndex($posts);
    }
}
