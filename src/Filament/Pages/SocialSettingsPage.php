<?php

namespace Bambamboole\MyCms\Filament\Pages;

use Bambamboole\MyCms\Settings\SocialSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class SocialSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Social Settings';

    protected static ?string $title = 'Social Settings';

    protected static string $settings = SocialSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('x_link')->nullable()->url(),
                Forms\Components\TextInput::make('github_link')->nullable()->url(),
                Forms\Components\TextInput::make('linked_in_link')->nullable()->url(),
            ]);
    }
}
