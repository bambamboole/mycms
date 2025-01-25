<?php

namespace Bambamboole\MyCms\Tests\Browser;

use Bambamboole\MyCms\Models\Page;
use Bambamboole\MyCms\Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class PageTest extends DuskTestCase
{
    public function test_home_page_is_properly_routed()
    {
        self::markTestSkipped('DUsk setup with migrations not working properly');

        Page::factory()->create([
            'title' => 'Home page',
            'slug' => '/',
            'content' => 'MyCMS Homepage here!',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('MyCMS Homepage here!');
        });
    }

    public function test_pages_are_properly_routed()
    {
        self::markTestSkipped('DUsk setup with migrations not working properly');

        Page::factory()->create([
            'title' => 'Test Page',
            'slug' => 'foo',
            'content' => 'MyCMS is awesome!',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/foo')
                ->assertSee('MyCMS is awesome!');
        });
    }
}
