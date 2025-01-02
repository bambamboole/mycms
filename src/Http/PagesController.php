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
            ->where('slug', '/'.ltrim((string) $request->uri()->path(), '/'))
            ->firstOrFail();

        return view(MyCms::getPageView(), ['page' => $page]);
    }
}
