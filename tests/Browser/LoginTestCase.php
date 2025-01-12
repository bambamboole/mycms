<?php

use Bambamboole\MyCms\Models\Page;
use Laravel\Dusk\Browser;

it('test example', function () {
    Page::factory()->create([
        'title' => 'Test Page',
        'slug' => '/',
        'content' => 'MyCMS is awesome!',
    ]);
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->waitFor('#foo', 10)
            ->assertSee('MyCMS is awesome!');
    });
});
