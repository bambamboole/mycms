<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Theme;

use Bambamboole\MyCms\Models\Page;
use Bambamboole\MyCms\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Tags\Tag;

class BlankTheme implements ThemeInterface
{
    public function menuLocations(): array
    {
        return [];
    }

    public function getPageView(Page $page): string
    {
        return json_encode($page);
    }

    public function getPostIndexView(LengthAwarePaginator $posts)
    {
        return json_encode($posts);
    }

    public function getTagView(Tag $tag, LengthAwarePaginator $posts)
    {
        return json_encode([$tag, $posts]);
    }

    public function getPostView(Post $post)
    {
        return json_encode($post);
    }
}
