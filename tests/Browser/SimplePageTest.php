<?php

namespace Bambamboole\MyCms\Tests\Browser;

use Bambamboole\MyCms\Models\Page;
use Bambamboole\MyCms\Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class SimplePageTest extends DuskTestCase
{
    public function test_basic()
    {
        Page::factory()->create([
            'title' => 'Test Page',
            'slug' => 'foo',
            'content' => 'MyCMS is awesome!',
        ]);

        dump(Page::all());
        dump(config('database.connections.sqlite.database'));

        $this->browse(function (Browser $browser) {
            $browser->visit('/foo')
                ->waitFor('#foo', 20)
                ->assertSee('MyCMS is awesome!');
        });
    }
}
