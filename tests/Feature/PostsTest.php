<?php

use Bambamboole\MyCms\Models\Post;

it('shows posts index on /blog', function () {
    $posts = Post::factory()
        ->published()
        ->count(2)
        ->create();

    $this->get('/blog')
        ->assertSeeText($posts->map->title->toArray());
});
