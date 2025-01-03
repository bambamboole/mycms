<?php

declare(strict_types=1);

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Settings\GeneralSettings;
use Bambamboole\MyCms\Settings\SocialSettings;
use Datlechin\FilamentMenuBuilder\Models\Menu;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;

readonly class MyCms
{
    public function __construct(protected Repository $config) {}

    public function getPageView(): string
    {
        return $this->config->get('mycms.theme.views.page_view');
    }

    public function getPostIndexView(): string
    {
        return $this->config->get('mycms.theme.views.post_index_view');
    }

    public function getPostView(): string
    {
        return $this->config->get('mycms.theme.views.post_view');
    }

    public function getTagView(): string
    {
        return $this->config->get('mycms.theme.views.tag_view');
    }

    public function getGeneralSettings()
    {
        return app(GeneralSettings::class);
    }

    public function getSocialSettings()
    {
        return app(SocialSettings::class);
    }

    public function getMenuItems(string $location): Collection
    {
        $menu = Menu::location($location);

        return $menu ? $menu->menuItems : collect();
    }
}
