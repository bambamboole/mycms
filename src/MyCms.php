<?php

declare(strict_types=1);

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Blocks\BlockRegistry;
use Bambamboole\MyCms\Blocks\ImageBlock;
use Bambamboole\MyCms\Blocks\MarkdownBlock;
use Bambamboole\MyCms\Models\Menu;
use Bambamboole\MyCms\Settings\GeneralSettings;
use Bambamboole\MyCms\Theme\ThemeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class MyCms
{
    protected array $menuLocations = [];

    protected array $settings = [GeneralSettings::class];

    protected array $defaultBlocks = [
        MarkdownBlock::class,
        ImageBlock::class,
    ];

    protected array $layouts = [];

    public function __construct(protected BlockRegistry $blockRegistry, protected ThemeInterface $theme)
    {
        foreach ($this->defaultBlocks as $block) {
            $this->blockRegistry->register(app($block));
        }
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

    public function blocks(): BlockRegistry
    {
        return $this->blockRegistry;
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

    public function registerLayout(string $className): self
    {
        Blade::component($className::getId(), $className);
        $this->layouts[$className::getId()] = $className;

        return $this;
    }

    public function getLayouts(): array
    {
        return $this->layouts;
    }
}
