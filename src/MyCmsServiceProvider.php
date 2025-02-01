<?php

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Blocks\BlockRegistry;
use Bambamboole\MyCms\Commands\InstallCommand;
use Bambamboole\MyCms\Commands\PublishCommand;
use Bambamboole\MyCms\Commands\UpdateCommand;
use Bambamboole\MyCms\Models\Menu;
use Bambamboole\MyCms\Models\Page;
use Bambamboole\MyCms\Models\Post;
use Bambamboole\MyCms\Policies\MenuPolicy;
use Bambamboole\MyCms\Policies\PagePolicy;
use Bambamboole\MyCms\Policies\PostPolicy;
use Bambamboole\MyCms\Policies\RolePolicy;
use Bambamboole\MyCms\Policies\UserPolicy;
use Bambamboole\MyCms\Resources\MenuResource\Livewire\CreateCustomLink;
use Bambamboole\MyCms\Resources\MenuResource\Livewire\CreateCustomText;
use Bambamboole\MyCms\Resources\MenuResource\Livewire\MenuItems;
use Bambamboole\MyCms\Resources\MenuResource\Livewire\MenuPanel;
use Bambamboole\MyCms\Theme\ThemeInterface;
use Bambamboole\MyCms\Widgets\HealthCheckResultWidget;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\Facades\Gate;
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
use Spatie\Permission\Models\Role;
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
                'create_menus_table',
                'create_pages_table',
                'create_posts_table',
                '../settings/create_general_settings',
            ])
            ->hasTranslations()
            ->hasCommands([InstallCommand::class, UpdateCommand::class, PublishCommand::class]);

    }

    public function registeringPackage()
    {
        $this->app->singleton(BlockRegistry::class);
        $this->app->singleton(ThemeInterface::class, fn () => $this->app->make(config('mycms.theme')));
        $this->app->singleton(MyCms::class);
        $this->app->singleton(MyCmsPlugin::class);
        $this->app->bind(SettingsContainer::class, fn () => new Settings\MyCmsSettingsContainer($this->app->make(Container::class)));
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

        $config->set('filament-shield.shield_resource.show_model_path', false);
        $config->set('filament-shield.permission_prefixes.resource', [
            'view',
            'view_any',
            'create',
            'replicate',
            'update',
            'delete',
            'delete_any',
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

        if (config('torchlight.token') !== null) {
            $this->app->afterResolving(
                Kernel::class,
                fn (Kernel $kernel) => $kernel->prependMiddleware(RenderTorchlight::class),
            );
        }
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName(),
        );
        Livewire::component('health-check-result', HealthCheckResultWidget::class);
        Livewire::component('menu-builder-items', MenuItems::class);
        Livewire::component('menu-builder-panel', MenuPanel::class);
        Livewire::component('create-custom-link', CreateCustomLink::class);
        Livewire::component('create-custom-text', CreateCustomText::class);

        Health::checks([
            EnvironmentCheck::new(),
            OptimizedAppCheck::new(),
            ScheduleCheck::new(),
            CacheCheck::new(),
        ]);

        Gate::policy(config('mycms.models.user'), UserPolicy::class);
        Gate::policy(Menu::class, MenuPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Page::class, PagePolicy::class);
        Gate::policy(Post::class, PostPolicy::class);

        Gate::before(function ($user) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }

    protected function getAssetPackageName(): ?string
    {
        return 'mycms';
    }

    protected function getAssets(): array
    {
        return [
            AlpineComponent::make('menu', __DIR__.'/../resources/dist/js/menu.js'),
            Css::make('index', __DIR__.'/../resources/dist/css/index.css'),
            Css::make('admin-bar', __DIR__.'/../resources/dist/css/admin-bar.css'),
        ];
    }
}
