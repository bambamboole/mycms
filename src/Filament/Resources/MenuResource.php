<?php
declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Resources;

use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Menu;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    public static function getModel(): string
    {
        return Menu::class;
    }

    public static function getNavigationLabel(): string
    {
        return __('mycms::menu.resource.navigation-label');
    }

    public static function getNavigationIcon(): string
    {
        return __('mycms::menu.resource.navigation-icon');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('mycms::menu.resource.navigation-group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Components\Grid::make(4)
                    ->schema([
                        Components\TextInput::make('name')
                            ->label(__('mycms::menu.resource.fields.name.label'))
                            ->required()
                            ->columnSpan(3),

                        Components\ToggleButtons::make('is_visible')
                            ->grouped()
                            ->options([
                                true => __('mycms::menu.resource.fields.is_visible.visible'),
                                false => __('mycms::menu.resource.fields.is_visible.hidden'),
                            ])
                            ->colors([
                                true => 'primary',
                                false => 'danger',
                            ])
                            ->required()
                            ->label(__('mycms::menu.resource.fields.is_visible.label'))
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $locations = MyCms::getMenuLocations();

        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('menuItems'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('mycms::menu.resource.fields.name.label')),
                Tables\Columns\TextColumn::make('locations.location')
                    ->label(__('mycms::menu.resource.fields.locations.label'))
                    ->default(__('mycms::menu.resource.fields.locations.empty'))
                    ->color(fn (string $state) => array_key_exists($state, $locations) ? 'primary' : 'gray')
                    ->formatStateUsing(fn (string $state) => $locations[$state] ?? $state)
                    ->limitList(2)
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('menu_items_count')
                    ->label(__('mycms::menu.resource.fields.items.label'))
                    ->icon('heroicon-o-link')
                    ->numeric()
                    ->default(0)
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label(__('mycms::menu.resource.fields.is_visible.label'))
                    ->sortable()
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => MenuResource\Pages\ListMenus::route('/'),
            'edit' => MenuResource\Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
