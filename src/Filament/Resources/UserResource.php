<?php

namespace Bambamboole\MyCms\Filament\Resources;

use Bambamboole\MyCms\Filament\Resources\UserResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class UserResource extends Resource
{
    public static function getModel(): string
    {
        return config('mycms.models.user');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('mycms::resources/user.fields.name.label')
                    ->translateLabel()
                    ->helperText(__('mycms::resources/user.fields.name.helper-text'))
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                TextInput::make('email')
                    ->label('mycms::resources/user.fields.email.label')
                    ->translateLabel()
                    ->helperText(__('mycms::resources/user.fields.email.helper-text'))
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('mycms::resources/user.table.columns.id')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('mycms::resources/user.table.columns.name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('mycms::resources/user.table.columns.email')
                    ->translateLabel()
                    ->copyable()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('mycms::resources/user.table.actions.edit')
                    ->translateLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('mycms::resources/user.table.bulk-actions.delete')
                        ->translateLabel(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return parent::getNavigationGroup() ?? __('mycms::resources/user.navigation-group');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return parent::getNavigationIcon() ?? __('mycms::resources/user.navigation-icon');
    }

    public static function getNavigationLabel(): string
    {
        return __('mycms::resources/user.navigation-label');
    }
}
