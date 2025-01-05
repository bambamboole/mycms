<?php

declare(strict_types=1);

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Filament\Pages\SettingsPage;
use Bambamboole\MyCms\Filament\Resources\PageResource;
use Bambamboole\MyCms\Filament\Resources\PostResource;
use Bambamboole\MyCms\Filament\Resources\RoleResource;
use Bambamboole\MyCms\Filament\Resources\UserResource;
use Bambamboole\MyCms\Models\Page;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use Datlechin\FilamentMenuBuilder\MenuPanel\ModelMenuPanel;
use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Auth\EditProfile;
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
        ]);
        if (config('mycms.environment_indicator.enabled')) {
            $panel->plugin(EnvironmentIndicatorPlugin::make());
        }
        if (config('mycms.application_health.enabled')) {
            $panel->plugin(FilamentSpatieLaravelHealthPlugin::make()->navigationGroup('Admin')->navigationLabel('Application Health'));
        }
        $panel->resources([PageResource::class, PostResource::class, UserResource::class, RoleResource::class]);
        $panel->pages([
            SettingsPage::class,
        ]);
        $menuPlugin = FilamentMenuBuilderPlugin::make()
            ->addMenuPanel(ModelMenuPanel::make()->model(Page::class))
            ->navigationGroup('Admin');
        foreach (config('mycms.theme.menus') as $key => $label) {
            $menuPlugin->addLocation($key, $label);
        }
        $panel->plugin($menuPlugin);

        $panel->profile(EditProfile::class, false);
        $panel->plugin(FilamentShieldPlugin::make());
        $panel->renderHook(
            PanelsRenderHook::TOPBAR_START,
            fn () => Blade::render(sprintf('<x-filament::link href="/">%s</x-filament::link>', __('mycms::general.go-to-site-link'))),
        );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
