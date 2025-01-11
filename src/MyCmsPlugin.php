<?php

declare(strict_types=1);

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Filament\Pages\SettingsPage;
use Bambamboole\MyCms\Filament\Pages\SiteHealthPage;
use Bambamboole\MyCms\Filament\Resources\PageResource;
use Bambamboole\MyCms\Filament\Resources\PostResource;
use Bambamboole\MyCms\Filament\Resources\RoleResource;
use Bambamboole\MyCms\Filament\Resources\UserResource;
use Bambamboole\MyCms\Models\Page;
use Bambamboole\MyCms\Theme\ThemeInterface;
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

class MyCmsPlugin implements Plugin
{
    public function __construct(protected ThemeInterface $theme) {}

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
        $panel->resources([PageResource::class, PostResource::class, UserResource::class, RoleResource::class]);
        $panel->pages([
            SettingsPage::class,
            SiteHealthPage::class,
        ]);
        $panel->widgets([PostResource\Widgets\LatestPostWidget::class]);
        $menuPlugin = FilamentMenuBuilderPlugin::make()
            ->addMenuPanel(ModelMenuPanel::make()->model(Page::class))
            ->navigationGroup('Admin');
        foreach ($this->theme->menuLocations() as $key => $label) {
            $menuPlugin->addLocation($key, $label);
        }
        $panel->plugin($menuPlugin);

        $panel->profile(EditProfile::class, false);
        $panel->plugin(FilamentShieldPlugin::make());
        $panel->renderHook(
            PanelsRenderHook::TOPBAR_START,
            fn () => Blade::render(sprintf('<x-filament::link href="/">%s</x-filament::link>', __('mycms::general.go-to-site-link'))),
        );

        if (method_exists($this->theme, 'configurePanel')) {
            $this->theme->configurePanel($panel);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
