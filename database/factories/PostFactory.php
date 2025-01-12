<?php

namespace Bambamboole\MyCms\Database\Factories;

use Bambamboole\MyCms\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/** @extends Factory<Post> */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /** @return array<string, mixed> */
    public function definition()
    {
        $title = fake()->unique()->sentence;

        return [
            'author_id' => config('mycms.models.user')::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence, // secret
            'content' => fake()->paragraphs(rand(3, 10), true),
        ];
    }

    public function published(?Carbon $publishedAt = null): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => $publishedAt ?? now()->subDays(rand(0, 365)),
        ]);
    }
}
