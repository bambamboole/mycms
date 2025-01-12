<?php

namespace Bambamboole\MyCms\Http;

use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesController
{
    public function show(Request $request, string $slug = '/')
    {
        dump(config('database.connections.sqlite.database'));
        DB::reconnect();
        dd(Page::query()->get());
        $page = Page::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return MyCms::theme()->getPageView($page);
    }
}
