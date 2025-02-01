<?php

namespace Bambamboole\MyCms\Models;

use Bambamboole\MyCms\Database\Factories\PageFactory;
use Bambamboole\MyCms\Resources\MenuResource\MenuPanel\MenuPanelable;
use Illuminate\Database\Eloquent\Builder;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Page extends BasePostType implements MenuPanelable
{
    protected static string $factory = PageFactory::class;

    public function getMenuPanelTitleColumn(): string
    {
        return 'title';
    }

    public function getMenuPanelUrlUsing(): callable
    {
        return fn (self $model) => '/'.ltrim($model->slug, '/');
    }

    public function getMenuPanelName(): string
    {
        return 'Pages';
    }

    public function getMenuPanelModifyQueryUsing(): callable
    {
        return fn (Builder $query) => $query;
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            author: property_exists($this->author, 'name') ? $this->author->name : null,
            published_time: $this->published_at,
            modified_time: $this->updated_at,
        );
    }
}
