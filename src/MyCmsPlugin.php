<?php declare(strict_types=1);

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Filament\Pages\GeneralSettingsPage;
use Bambamboole\MyCms\Filament\Pages\SocialSettingsPage;
use Bambamboole\MyCms\Filament\Resources\PageResource;
use Bambamboole\MyCms\Filament\Resources\PostResource;
use Bambamboole\MyCms\Models\Page;
use Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use Datlechin\FilamentMenuBuilder\MenuPanel\ModelMenuPanel;
use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class MyCmsPlugin implements Plugin
{
    public static function make(): self
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'mycms';
    }

    public function register(Panel $panel): void
    {
        $panel->navigationGroups([
            NavigationGroup::make('Content')->collapsible(false),
            NavigationGroup::make('Admin')->collapsible()->collapsed(),
            NavigationGroup::make('Settings')->collapsible()->collapsed(),
        ]);
        if (config('mycms.environment_indicator.enabled')) {
            $panel->plugin(EnvironmentIndicatorPlugin::make());
        }
        if (config('mycms.application_health.enabled')) {
            $panel->plugin(FilamentSpatieLaravelHealthPlugin::make()->navigationGroup('Admin')->navigationLabel('Application Health'));
        }
        $panel->resources([PageResource::class, PostResource::class]);
        $panel->pages([
            GeneralSettingsPage::class,
            SocialSettingsPage::class,
        ]);
        $panel->plugin(FilamentMenuBuilderPlugin::make()
            ->addMenuPanel(ModelMenuPanel::make()->model(Page::class))
            ->addLocation('header', 'Header')
            ->addLocation('footer', 'Footer')
            ->navigationGroup('Admin'));
    }

    public function boot(Panel $panel): void
    {
        $panel->renderHook(
            PanelsRenderHook::TOPBAR_START,
            fn() => Blade::render('<x-filament::link href="/">Go to Site</x-filament::link>'),
        );
    }
}
