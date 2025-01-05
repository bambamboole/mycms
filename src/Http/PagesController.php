<?php

namespace Bambamboole\MyCms\Http;

use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PagesController
{
    public function show(Request $request): View
    {
        $page = Page::query()
            ->where('slug', $request->uri()->path())
            ->firstOrFail();

        return MyCms::theme()->getPageView($page);
    }
}
