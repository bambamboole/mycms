<?php

use Bambamboole\MyCms\Http\PagesController;
use Bambamboole\MyCms\Http\PostsController;
use Bambamboole\MyCms\Http\TagsController;

Route::get('/blog', [PostsController::class, 'index'])->name('posts.index');
Route::get('/blog/{slug}', [PostsController::class, 'show'])->name('posts.show');
Route::get('/tags/{slug}', [TagsController::class, 'show'])->name('tags.show');

Route::feeds();

Route::fallback([PagesController::class, 'show']);
