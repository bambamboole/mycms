<?php

declare(strict_types=1);

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Settings\GeneralSettings;
use Bambamboole\MyCms\Theme\ThemeInterface;
use Datlechin\FilamentMenuBuilder\Models\Menu;
use Illuminate\Support\Collection;

readonly class MyCms
{
    public function __construct(protected ThemeInterface $theme) {}

    public function registeredSettings(): array
    {
        return array_merge([
            GeneralSettings::class,
        ], method_exists($this->theme, 'registeredSettings') ? $this->theme->registeredSettings() : []);
    }

    public function getGeneralSettings(): GeneralSettings
    {
        return app(GeneralSettings::class);
    }

    public function getMenuItems(string $location): Collection
    {
        $menu = Menu::location($location);

        return $menu ? $menu->menuItems : collect();
    }

    public function theme(): ThemeInterface
    {
        return $this->theme;
    }
}
