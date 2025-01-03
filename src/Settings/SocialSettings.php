<?php

declare(strict_types=1);

namespace Bambamboole\MyCms\Settings;

use Filament\Forms\Components\TextInput;
use Spatie\LaravelSettings\Settings;

class SocialSettings extends Settings implements MyCmsSettingsInterface
{
    public ?string $x_link = null;

    public ?string $github_link = null;

    public ?string $linked_in_link = null;

    public static function group(): string
    {
        return 'social';
    }

    public function form(): array
    {
        return [
            TextInput::make('x_link')->nullable()->url(),
            TextInput::make('github_link')->nullable()->url(),
            TextInput::make('linked_in_link')->nullable()->url(),
        ];
    }

    public function submit(array $payload): void
    {
        $this->x_link = $payload['x_link'];
        $this->github_link = $payload['github_link'];
        $this->linked_in_link = $payload['linked_in_link'];
        $this->save();
    }
}
