<?php

namespace Bambamboole\MyCms\Http;

use Bambamboole\MyCms\Models\Post;
use Bambamboole\MyCms\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Spatie\Feed\Feed;
use Spatie\Feed\Helpers\ResolveFeedItems;

class FeedController
{
    public function __invoke(Request $request, GeneralSettings $settings): Feed
    {
        $items = ResolveFeedItems::resolve('main', [Post::class, 'getFeedItems']);

        return new Feed(
            $settings->site_name,
            $items,
            $request->url(),
            'feed::atom',
            $settings->description,
            'en-US',
            '',
            'atom',
            '',
        );
    }
}
