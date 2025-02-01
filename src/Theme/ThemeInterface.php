<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Theme;

use Bambamboole\MyCms\Models\BasePostType;
use Bambamboole\MyCms\MyCms;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ThemeInterface
{
    public function configure(MyCms $myCms): void;

    public function render(BasePostType $post);

    public function renderIndex(LengthAwarePaginator $collection);
}
