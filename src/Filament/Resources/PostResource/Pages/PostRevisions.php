<?php

declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Resources\PostResource\Pages;

use Bambamboole\MyCms\Filament\Resources\PostResource;
use Mansoor\FilamentVersionable\RevisionsPage;

class PostRevisions extends RevisionsPage
{
    protected static string $resource = PostResource::class;
}
