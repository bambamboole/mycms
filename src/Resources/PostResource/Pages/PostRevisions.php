<?php

declare(strict_types=1);

namespace Bambamboole\MyCms\Resources\PostResource\Pages;

use Bambamboole\MyCms\Resources\PostResource;
use Mansoor\FilamentVersionable\RevisionsPage;

class PostRevisions extends RevisionsPage
{
    protected static string $resource = PostResource::class;
}
