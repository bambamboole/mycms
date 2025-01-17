<?php

declare(strict_types=1);

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Models\Menu;
use Bambamboole\MyCms\Settings\GeneralSettings;
use Bambamboole\MyCms\Theme\ThemeInterface;
use Illuminate\Support\Collection;

class MyCms
{
    protected array $menuLocations = [];

    protected array $settings = [GeneralSettings::class];

    public function __construct(protected ThemeInterface $theme)
    {
        $this->theme->configure($this);
    }

    public function registeredSettings(): array
    {
        return $this->settings;
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

    public function registerMenuLocation(string $key, string $label): self
    {
        $this->menuLocations[$key] = $label;

        return $this;
    }

    public function getMenuLocations(): array
    {
        return $this->menuLocations;
    }

    public function registerSettings(string $settingClass): self
    {
        $this->settings[] = $settingClass;

        return $this;
    }
}
