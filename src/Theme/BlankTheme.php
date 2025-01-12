<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Theme;

use Bambamboole\MyCms\Models\Page;
use Bambamboole\MyCms\Models\Post;
use Bambamboole\MyCms\MyCms;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Spatie\Tags\Tag;

class BlankTheme implements ThemeInterface
{
    public function configure(MyCms $myCms): void
    {
        $myCms
            ->registerMenuLocation('header', 'Header')
            ->registerMenuLocation('footer', 'Footer');
    }

    public function getPageView(Page $page): View
    {
        return view('mycms::themes.blank.pages-show', ['page' => $page]);
    }

    public function getPostIndexView(LengthAwarePaginator $posts): View
    {
        return view('mycms::themes.blank.posts-index', ['posts' => $posts]);
    }

    public function getTagView(Tag $tag, LengthAwarePaginator $posts): View
    {
        return view('mycms::themes.blank.posts-index', ['tag' => $tag, 'posts' => $posts]);
    }

    public function getPostView(Post $post): View
    {
        return view('mycms::themes.blank.posts-show', ['post' => $post]);
    }
}
