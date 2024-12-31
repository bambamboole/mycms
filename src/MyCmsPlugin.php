<?php declare(strict_types=1);

namespace Bambamboole\MyCms;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
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
            FilamentSpatieLaravelHealthPlugin::make()->navigationGroup('Admin')->navigationLabel('Application Health');
        }
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
