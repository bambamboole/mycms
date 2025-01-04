<?php

declare(strict_types=1);

namespace Bambamboole\MyCms\Settings;

use Filament\Forms\Components\TextInput;
use Spatie\LaravelSettings\Settings;

class SocialSettings extends Settings implements MyCmsSettingsInterface
{
    public ?string $x_username = null;

    public ?string $linkedin_username = null;

    public ?string $github_username = null;

    public static function group(): string
    {
        return 'social';
    }

    public function form(): array
    {
        return [
            TextInput::make('x_username')
                ->prefix('https://x.com/')
                ->nullable()
                ->helperText('Your X username. Keeping blank will also remove the X icon from the footer.'),
            TextInput::make('linkedin_username')
                ->prefix('https://www.linkedin.com/in/')
                ->nullable()
                ->helperText('Your LinkedIn username. Keeping blank will also remove the LinkedIn icon from the footer.'),
            TextInput::make('github_username')
                ->prefix('https://github.com/')
                ->nullable()
                ->helperText('Your GitHub username. Keeping blank will also remove the GitHub icon from the footer.'),
        ];
    }

    public function submit(array $payload): void
    {
        $this->x_username = $payload['x_username'];
        $this->github_username = $payload['github_username'];
        $this->linkedin_username = $payload['linkedin_username'];
        $this->save();
    }

    public function getGitHubProfileLink(): ?string
    {
        return $this->github_username ? 'https://github.com/'.$this->github_username : null;
    }

    public function getLinkedInProfileLink(): ?string
    {
        return $this->linkedin_username ? 'https://www.linkedin.com/in/'.$this->linkedin_username : null;
    }

    public function getXProfileLink(): ?string
    {
        return $this->x_username ? 'https://x.com/'.$this->x_username : null;
    }
}
