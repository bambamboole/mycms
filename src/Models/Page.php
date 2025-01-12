<?php

namespace Bambamboole\MyCms\Models;

use Bambamboole\MyCms\Database\Factories\PageFactory;
use Bambamboole\MyCms\Torchlight\TorchlightExtension;
use Datlechin\FilamentMenuBuilder\Concerns\HasMenuPanel;
use Datlechin\FilamentMenuBuilder\Contracts\MenuPanelable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use RalphJSmit\Laravel\SEO\Models\SEO;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RyanChandler\CommonmarkBladeBlock\BladeExtension;

/**
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property ?Carbon $published_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property User $author
 * @property SEO $seo
 */
class Page extends Model implements MenuPanelable
{
    use HasFactory;
    use HasMenuPanel;
    use HasSEO;

    protected static string $factory = PageFactory::class;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $page) {
            if (!$page->author_id) {
                $page->author_id = auth()->id();
            }
        });
    }

    public function contentAsHtml(): string
    {
        $extensions = [new BladeExtension, new AttributesExtension];
        if (config('torchlight.token') !== null) {
            $extensions[] = new TorchlightExtension;
        }

        return Str::markdown($this->content, extensions: $extensions);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(config('mycms.models.user'), 'author_id');
    }

    public function getMenuPanelTitleColumn(): string
    {
        return 'title';
    }

    public function getMenuPanelUrlUsing(): callable
    {
        return fn (self $model) => $model->slug;
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
