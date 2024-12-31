<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Settings;

use Spatie\LaravelSettings\Settings;

class SocialSettings extends Settings
{
    public ?string $x_link = null;

    public ?string $github_link = null;

    public ?string $linked_in_link = null;

    public static function group(): string
    {
        return 'social';
    }
}
