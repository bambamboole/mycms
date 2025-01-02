<?php

declare(strict_types=1);

namespace Bambamboole\MyCms\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public static function group(): string
    {
        return 'general';
    }
}
