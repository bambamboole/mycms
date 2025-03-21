<?php

declare(strict_types=1);

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Pages\SettingsPage;
use Bambamboole\MyCms\Pages\SiteHealthPage;
use Bambamboole\MyCms\Resources\MenuResource;
use Bambamboole\MyCms\Resources\PageResource;
use Bambamboole\MyCms\Resources\PostResource;
use Bambamboole\MyCms\Resources\RoleResource;
use Bambamboole\MyCms\Resources\UserResource;
use Bambamboole\MyCms\Theme\ThemeInterface;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Auth\EditProfile;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Pboivin\FilamentPeek\FilamentPeekPlugin;

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

        $panel->resources([PageResource::class, PostResource::class, UserResource::class, RoleResource::class, MenuResource::class]);
        $panel->pages([
            SettingsPage::class,
            SiteHealthPage::class,
        ]);
        $panel->widgets([PostResource\Widgets\LatestPostWidget::class]);

        $panel->profile(EditProfile::class, false);
        $panel->plugin(FilamentShieldPlugin::make());
        $panel->plugin(FilamentPeekPlugin::make());
        $panel->renderHook(
            PanelsRenderHook::TOPBAR_START,
            fn () => Blade::render(sprintf('<x-filament::link href="/">%s</x-filament::link>', __('mycms::general.go-to-site-link'))),
        );

        $panel->renderHook('panels::global-search.before', function () {
            $env = app()->environment();

            return view('mycms::snippets.environment-indicator', [
                'color' => match ($env) {
                    'production' => Color::Red,
                    'staging' => Color::Orange,
                    'development' => Color::Blue,
                    default => Color::Pink,
                },
                'environment' => $env,
            ]);
        });

        if (method_exists($this->theme, 'configurePanel')) {
            $this->theme->configurePanel($panel);
        }

        $panel->sidebarFullyCollapsibleOnDesktop()->sidebarWidth('14rem');
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
