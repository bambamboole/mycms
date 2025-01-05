<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Theme;

use Bambamboole\MyCms\Models\Page;
use Bambamboole\MyCms\Models\Post;
use Bambamboole\MyCms\Settings\SocialSettings;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Spatie\Tags\Tag;

class DefaultTheme implements ThemeInterface
{
    public function menuLocations(): array
    {
        return [
            'header' => 'Header',
            'footer' => 'Footer',
        ];
    }

    public function getPageView(Page $page): View
    {
        return \view('mycms::themes.default.pages.show', ['page' => $page]);
    }

    public function getPostIndexView(LengthAwarePaginator $posts): View
    {
        return \view('mycms::themes.default.posts.index', ['posts' => $posts]);
    }

    public function getPostView(Post $post): View
    {
        return \view('mycms::themes.default.posts.show', ['post' => $post]);
    }

    public function getTagView(Tag $tag, LengthAwarePaginator $posts): View
    {
        return \view('mycms::themes.default.tags.show', ['tag' => $tag, 'posts' => $posts]);
    }

    public function registeredSettings(): array
    {
        return [SocialSettings::class];
    }
}
