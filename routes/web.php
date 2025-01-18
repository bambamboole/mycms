<?php

use Bambamboole\MyCms\Http\FeedController;
use Bambamboole\MyCms\Http\Middleware\InjectAdminBar;
use Bambamboole\MyCms\Http\PagesController;
use Bambamboole\MyCms\Http\PostsController;
use Bambamboole\MyCms\Http\TagsController;

Route::middleware(['web', InjectAdminBar::class])->group(function () {

    Route::get('/blog', [PostsController::class, 'index'])->name('mycms.posts.index');
    Route::get('/blog/{slug}', [PostsController::class, 'show'])->name('mycms.posts.show');
    Route::get('/tags/{slug}', [TagsController::class, 'show'])->name('mycms.tags.show');

    Route::get('/rss', FeedController::class)->name('mycms.feed');

    Route::get('/{slug?}', [PagesController::class, 'show'])->where('slug', '.*')->name('mycms.page')->fallback();
});
