<?php

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Commands\InstallCommand;
use Bambamboole\MyCms\Commands\PublishCommand;
use Bambamboole\MyCms\Commands\UpdateCommand;
use Bambamboole\MyCms\Filament\Widgets\HealthCheckResultWidget;
use Bambamboole\MyCms\Theme\ThemeInterface;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Http\Kernel;
use Livewire\Livewire;
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
use Spatie\LaravelSettings\SettingsContainer;
use Torchlight\Middleware\RenderTorchlight;

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
            ])
            ->hasTranslations()
            ->hasCommands([InstallCommand::class, UpdateCommand::class, PublishCommand::class]);

    }

    public function registeringPackage()
    {
        $this->app->singleton(ThemeInterface::class, fn () => $this->app->make(config('mycms.theme')));
        $this->app->singleton(MyCms::class);
        $this->app->singleton(MyCmsPlugin::class);
        $this->app->bind(SettingsContainer::class, fn () => new Settings\MyCmsSettingsContainer($this->app));
    }

    public function bootingPackage(): void
    {
        $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(RunHealthChecksCommand::class)->everyMinute();
        });

        $config = $this->app->make(Repository::class);

        $config->set('settings.migrations_paths', array_merge(
            $config->get('settings.migrations_paths', []),
            [$this->getPackageBaseDir().'/database/settings'],
        ));

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

        if (config('torchlight.token') !== null) {
            $this->app->afterResolving(
                Kernel::class,
                fn (Kernel $kernel) => $kernel->prependMiddleware(RenderTorchlight::class),
            );
        }
    }

    public function packageBooted()
    {
        Livewire::component('health-check-result', HealthCheckResultWidget::class);

        Health::checks([
            EnvironmentCheck::new(),
            OptimizedAppCheck::new(),
            ScheduleCheck::new(),
            CacheCheck::new(),
        ]);
    }
}
