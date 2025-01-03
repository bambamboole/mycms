<?php

declare(strict_types=1);

namespace Bambamboole\MyCms\Settings;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings implements MyCmsSettingsInterface
{
    public ?string $site_name;

    public ?string $description;

    public static function group(): string
    {
        return 'general';
    }

    public function form(): array
    {
        return [
            TextInput::make('site_name')->required(),
            Textarea::make('description')->rows(2),
        ];
    }

    public function submit(array $payload): void
    {
        $this->site_name = $payload['site_name'];
        $this->description = $payload['description'];
        $this->save();
    }
}
