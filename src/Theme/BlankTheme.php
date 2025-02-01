<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Theme;

use Bambamboole\MyCms\Models\BasePostType;
use Bambamboole\MyCms\MyCms;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;

class BlankTheme implements ThemeInterface
{
    public function configure(MyCms $myCms): void
    {
        $myCms
            ->registerMenuLocation('header', 'Header')
            ->registerMenuLocation('footer', 'Footer');

        $myCms->registerLayout(BaseLayout::class);
    }

    public function render(BasePostType $post): View
    {
        return view('mycms::themes.blank.show', ['post' => $post]);
    }

    public function renderIndex(LengthAwarePaginator $collection): View
    {
        return view('mycms::themes.blank.index', ['collection' => $collection]);
    }
}
