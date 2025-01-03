<?php

namespace Bambamboole\MyCms\Models;

use App\Models\User;
use Bambamboole\MyCms\Database\Factories\PostFactory;
use Bambamboole\MyCms\Torchlight\TorchlightExtension;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Overtrue\LaravelVersionable\Versionable;
use Overtrue\LaravelVersionable\VersionStrategy;
use RyanChandler\CommonmarkBladeBlock\BladeExtension;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Post extends Model implements Feedable, HasMedia
{
    use HasFactory, HasTags, InteractsWithMedia, Versionable;

    protected static string $factory = PostFactory::class;

    protected $guarded = [];

    protected array $versionable = ['title', 'slug', 'excerpt', 'content', 'published_at'];

    protected VersionStrategy $versionStrategy = VersionStrategy::SNAPSHOT;

    public static function boot()
    {
        parent::boot();

        static::creating(function (Post $post) {
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
        return $this->belongsTo(User::class, 'author_id');
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
        return Str::markdown($this->content, extensions: [new TorchlightExtension, new BladeExtension]);
    }

    /**
     * @return array|FeedItem
     */
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
}
