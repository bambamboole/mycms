<?php

namespace Bambamboole\MyCms\Models;

use Bambamboole\MyCms\Database\Factories\PageFactory;
use Datlechin\FilamentMenuBuilder\Concerns\HasMenuPanel;
use Datlechin\FilamentMenuBuilder\Contracts\MenuPanelable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use RyanChandler\CommonmarkBladeBlock\BladeExtension;
use Torchlight\Commonmark\V2\TorchlightExtension;

class Page extends Model implements MenuPanelable
{
    use HasFactory;
    use HasMenuPanel;

    protected static string $factory = PageFactory::class;

    protected $guarded = [];

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
        return fn (self $model) => $model->slug;
    }
}
