<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Theme;

use Bambamboole\MyCms\Models\Page;
use Bambamboole\MyCms\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Tags\Tag;

interface ThemeInterface
{
    public function menuLocations(): array;

    public function getPageView(Page $page);

    public function getPostIndexView(LengthAwarePaginator $posts);

    public function getTagView(Tag $tag, LengthAwarePaginator $posts);

    public function getPostView(Post $post);
}
