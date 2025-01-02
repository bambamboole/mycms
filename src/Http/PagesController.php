<?php

namespace Bambamboole\MyCms\Http;

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

        return view('mycms::themes.default.pages.show', ['page' => $page]);
    }
}
