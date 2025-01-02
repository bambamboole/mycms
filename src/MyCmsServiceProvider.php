<?php

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Commands\MyCmsInstallCommand;
use Bambamboole\MyCms\Models\Post;
use Bambamboole\MyCms\Settings\GeneralSettings;
use Bambamboole\MyCms\Settings\SocialSettings;
use Filament\Facades\Filament;
use Filament\View\PanelsRenderHook;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\Blade;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Facades\Health;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MyCmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('mycms')
            ->hasConfigFile()
            ->hasViews('mycms')
            ->hasRoute('web')
            ->hasMigrations([
                'create_pages_table',
                '../settings/create_general_settings',
                '../settings/create_social_settings',
            ])
            ->hasAssets()
            ->hasCommand(MyCmsInstallCommand::class);

    }

    public function bootingPackage(): void
    {
        $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(RunHealthChecksCommand::class)->everyMinute();
        });

        $config = $this->app->make(Repository::class);
        $config->set('settings.settings', array_merge(
            $config->get('settings.settings'),
            [
                GeneralSettings::class,
                SocialSettings::class,
            ]
        ));
        $config->set('settings.migrations_paths', array_merge(
            $config->get('settings.migrations_paths'),
            [$this->getPackageBaseDir().'/database/settings'],
        ));

        $config->set('feed.feeds.main.items', [Post::class, 'getFeedItems']);
        $config->set('feed.feeds.main.url', '/rss');
        // @TODO find a better way to register theme stuff
        $config->set('blade-icons.sets.default', [
            'path' => 'vendor/bambamboole/mycms/resources/views/themes/default/svg',
            'prefix' => 'icon',
        ]);

        Health::checks([
            EnvironmentCheck::new(),
            OptimizedAppCheck::new(),
            ScheduleCheck::new(),
            CacheCheck::new(),
        ]);

        Filament::registerRenderHook(
            PanelsRenderHook::TOPBAR_START,
            fn () => Blade::render('<x-filament::link href="/">Go to Site</x-filament::link>'),
        );
    }
}
