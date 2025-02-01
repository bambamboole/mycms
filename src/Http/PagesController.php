<?php

namespace Bambamboole\MyCms\Http;

use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Page;
use Illuminate\Support\Facades\Context;

class PagesController
{
    public function show(string $slug = '/')
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
        Context::add('current_page', $page);

        return MyCms::theme()->render($page);
    }
}
