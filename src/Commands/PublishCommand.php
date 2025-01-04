<?php

namespace Bambamboole\MyCms\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    public $signature = 'mycms:publish';

    public $description = 'Publish migrations and assets for MyCMS and its dependencies';

    public function handle(): int
    {
        // publish migrations form spatie/laravel-health
        $this->call('vendor:publish', ['--tag' => 'health-migrations']);
        // publish migrations form datlechin/filament-menu-builder
        $this->call('vendor:publish', ['--tag' => 'filament-menu-builder-migrations']);
        // publish migrations form spatie/laravel-settings
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\LaravelSettings\LaravelSettingsServiceProvider',
            '--tag' => 'migrations',
        ]);
        // publish migrations form spatie/laravel-medialibrary
        $this->call('vendor:publish', ['--tag' => 'medialibrary-migrations']);
        // Publish spatie/laravel-feed assets
        $this->call('vendor:publish', ['--tag' => 'feed-assets']);
        // Publish ralphjsmit/laravel-seo assets
        $this->call('vendor:publish', ['--tag' => 'seo-migrations']);
        // Publish spatie/laravel-permission migrations
        $this->call('vendor:publish', ['--tag' => 'permission-migrations']);

        $this->call('vendor:publish', ['--tag' => 'mycms-assets']);
        $this->call('vendor:publish', ['--tag' => 'mycms-migrations']);
        $this->call('vendor:publish', ['--tag' => 'mycms-settings-migrations']);
        $this->call('vendor:publish', ['--tag' => 'tags-migrations']);

        return self::SUCCESS;
    }
}
