<?php

declare(strict_types=1);

namespace Bambamboole\MyCms;

use Bambamboole\MyCms\Settings\GeneralSettings;
use Bambamboole\MyCms\Settings\SocialSettings;
use Illuminate\Contracts\Config\Repository;

readonly class MyCms
{
    public function __construct(protected Repository $config) {}

    public function getPageView(): string
    {
        return $this->config->get('mycms.theme.page_view');
    }

    public function getPostIndexView(): string
    {
        return $this->config->get('mycms.theme.post_index_view');
    }

    public function getPostView(): string
    {
        return $this->config->get('mycms.theme.post_view');
    }

    public function getTagView(): string
    {
        return $this->config->get('mycms.theme.tag_view');
    }

    public function getGeneralSettings()
    {
        return app(GeneralSettings::class);
    }

    public function getSocialSettings()
    {
        return app(SocialSettings::class);
    }
}
