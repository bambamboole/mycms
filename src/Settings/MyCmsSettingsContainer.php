<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Settings;

use Bambamboole\MyCms\Facades\MyCms;
use Illuminate\Support\Collection;
use Spatie\LaravelSettings\SettingsContainer;

class MyCmsSettingsContainer extends SettingsContainer
{
    public function getSettingClasses(): Collection
    {
        $settings = parent::getSettingClasses()
            ->merge(MyCms::registeredSettings())
            ->unique();

        return self::$settingsClasses = $settings;
    }
}
