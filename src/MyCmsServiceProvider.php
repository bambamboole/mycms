<?php

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Commands\InstallCommand;
use Bambamboole\MyCms\Commands\PublishCommand;
use Bambamboole\MyCms\Commands\UpdateCommand;
use Bambamboole\MyCms\Settings\GeneralSettings;
use Bambamboole\MyCms\Settings\SocialSettings;
use Bambamboole\MyCms\Theme\ThemeInterface;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Config\Repository;
use RalphJSmit\Laravel\SEO\SEOManager;
use RalphJSmit\Laravel\SEO\TagManager;
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
        $package
            ->name('mycms')
            ->hasConfigFile()
            ->hasViews('mycms')
            ->hasRoute('web')
            ->hasMigrations([
                'create_pages_table',
                'create_posts_table',
                '../settings/create_general_settings',
                '../settings/create_social_settings',
            ])
            ->hasTranslations()
            ->hasAssets()
            ->hasCommands([InstallCommand::class, UpdateCommand::class, PublishCommand::class]);

    }

    public function bootingPackage(): void
    {
        $this->app->singleton(ThemeInterface::class, fn () => $this->app->make(config('mycms.theme')));
        $this->app->singleton(MyCms::class);
        $this->app->singleton(MyCmsPlugin::class);
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

        $this->app->afterResolving(SEOManager::class, function (SEOManager $seoManager) {
            $siteName = \Bambamboole\MyCms\Facades\MyCms::getGeneralSettings()->site_name;
            config()->set('seo.title.homepage_title', $siteName);
            config()->set('seo.title.suffix', ' | '.$siteName);
            config()->set('seo.description.fallback', \Bambamboole\MyCms\Facades\MyCms::getGeneralSettings()->description);

            return $seoManager;
        });
        $this->app->afterResolving(TagManager::class, function (TagManager $tagManager) {
            $siteName = \Bambamboole\MyCms\Facades\MyCms::getGeneralSettings()->site_name;
            config()->set('seo.title.homepage_title', $siteName);
            config()->set('seo.title.suffix', ' | '.$siteName);
            config()->set('seo.description.fallback', \Bambamboole\MyCms\Facades\MyCms::getGeneralSettings()->description);

            return $tagManager;
        });
    }
}
