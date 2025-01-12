<?php

namespace Bambamboole\MyCms\Models;

use Bambamboole\MyCms\Database\Factories\PostFactory;
use Bambamboole\MyCms\Torchlight\TorchlightExtension;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use Overtrue\LaravelVersionable\Versionable;
use Overtrue\LaravelVersionable\VersionStrategy;
use RalphJSmit\Laravel\SEO\Models\SEO;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RyanChandler\CommonmarkBladeBlock\BladeExtension;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

/**
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $content
 * @property ?Carbon $published_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property User $author
 * @property SEO $seo
 */
class Post extends Model implements Feedable, HasMedia
{
    use HasFactory, HasSEO, HasTags, InteractsWithMedia, Versionable;

    protected static string $factory = PostFactory::class;

    protected $guarded = [];

    protected array $versionable = ['title', 'slug', 'excerpt', 'content', 'published_at'];

    protected VersionStrategy $versionStrategy = VersionStrategy::SNAPSHOT;

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $post) {
            if (!$post->author_id) {
                $post->author_id = auth()->id();
            }
        });
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('published_at', '<', now())
            ->orderBy('published_at', 'desc');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(config('mycms.models.user'), 'author_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);

        $this->addMediaConversion('featured_image')
            ->withResponsiveImages();
    }

    public function readingTime(): float
    {
        return ceil(str_word_count($this->content) / 250);
    }

    public function path(): string
    {
        return "/blog/{$this->slug}";
    }

    public function contentAsHtml(): string
    {
        $extensions = [new BladeExtension, new AttributesExtension];
        if (config('torchlight.token') !== null) {
            $extensions[] = new TorchlightExtension;
        }

        return Str::markdown($this->content, extensions: $extensions);
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->slug)
            ->title($this->title)
            ->summary($this->excerpt)
            ->updated($this->updated_at)
            ->link($this->path())
            ->authorName(optional($this->author)->name ?? '');
    }

    public function getFeedItems()
    {
        return self::published()->get();
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->excerpt,
            author: property_exists($this->author, 'name') ? $this->author->name : null,
            published_time: $this->published_at,
            modified_time: $this->updated_at,
        );
    }
}
