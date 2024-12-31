<?php declare(strict_types=1);

namespace Bambamboole\MyCms;

use Filament\Contracts\Plugin;
use Filament\Panel;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;

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
        if (config('mycms.environment_indicator.enabled')) {
            $panel->plugin(EnvironmentIndicatorPlugin::make());
        }
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
