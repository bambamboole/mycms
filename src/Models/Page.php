<?php

namespace Bambamboole\MyCms\Models;

use Bambamboole\MyCms\Database\Factories\PageFactory;
use Bambamboole\MyCms\Filament\Resources\MenuResource\MenuPanel\MenuPanelable;
use Bambamboole\MyCms\Torchlight\TorchlightExtension;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RyanChandler\CommonmarkBladeBlock\BladeExtension;

class Page extends BasePostType implements MenuPanelable
{
    protected static string $factory = PageFactory::class;

    public function contentAsHtml(): string
    {
        $extensions = [new BladeExtension, new AttributesExtension];
        if (config('torchlight.token') !== null) {
            $extensions[] = new TorchlightExtension;
        }

        return Str::markdown($this->content, extensions: $extensions);
    }

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
