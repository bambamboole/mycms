<?php

namespace Bambamboole\MyCms\Commands;

use Illuminate\Console\Command;

class MyCmsInstallCommand extends Command
{
    public $signature = 'mycms:install';

    public $description = 'This command installs mycms';

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
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
            '--tag' => 'medialibrary-migrations',
        ]);
        // Publish spatie/laravel-feed assets
        $this->call('vendor:publish', ['--tag' => 'feed-assets']);
        // Publish ralphjsmit/laravel-seo assets
        $this->call('vendor:publish', ['--tag' => 'seo-migrations']);

        $this->call('vendor:publish', ['--tag' => 'mycms-assets']);
        $this->call('vendor:publish', ['--tag' => 'mycms-migrations']);
        $this->call('vendor:publish', ['--tag' => 'mycms-settings-migrations']);
        $this->call('vendor:publish', ['--tag' => 'tags-migrations']);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
