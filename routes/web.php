<?php

use Bambamboole\MyCms\Http\PagesController;
use Bambamboole\MyCms\Http\PostsController;
use Bambamboole\MyCms\Http\TagsController;
use Illuminate\Http\RedirectResponse;

Route::get('/blog', [PostsController::class, 'index'])->name('posts.index');
Route::get('/blog/{slug}', [PostsController::class, 'show'])->name('posts.show');
Route::get('/tags/{slug}', [TagsController::class, 'show'])->name('tags.show');

Route::feeds();

// @TODO this route is only needed because the vite fonts won't have the correct path
Route::get('/resources/dist/assets/{assets}', fn (string $asset) => new RedirectResponse('/vendor/mycms/assets/'.$asset))->name('mycms.assets');
Route::fallback([PagesController::class, 'show']);
