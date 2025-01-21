<?php

namespace Bambamboole\MyCms\Filament\Resources;

use Bambamboole\MyCms\Blocks\BlockBuilder;
use Bambamboole\MyCms\Filament\Resources\PageResource\Pages;
use Bambamboole\MyCms\Filament\Resources\PageResource\Widgets\HomePageWidget;
use Bambamboole\MyCms\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->columnSpan('full')
                    ->tabs([
                        Tab::make('Content')
                            ->columns(4)
                            ->schema([
                                Forms\Components\Section::make()
                                    ->columnSpan(3)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('mycms::resources/page.fields.title.label')
                                            ->translateLabel()
                                            ->helperText(__('mycms::resources/page.fields.title.helper-text'))
                                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                                if (!$get('is_slug_changed_manually') && filled($state)) {
                                                    $set('slug', Str::slug($state));
                                                }
                                            })
                                            ->live(debounce: 300)
                                            ->required(),
                                        BlockBuilder::make('blocks'),
                                    ]),
                                Forms\Components\Section::make()
                                    ->columnSpan(1)
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('mycms::resources/page.fields.slug.label')
                                            ->translateLabel()
                                            ->helperText(__('mycms::resources/page.fields.slug.helper-text'))
                                            ->afterStateUpdated(function (Forms\Set $set) {
                                                $set('is_slug_changed_manually', true);
                                            })
                                            ->required(),
                                        Hidden::make('is_slug_changed_manually')
                                            ->default(false)
                                            ->dehydrated(false),
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('mycms::resources/page.fields.published_at.label')
                                            ->translateLabel()
                                            ->helperText(__('mycms::resources/page.fields.published_at.helper-text'))
                                            ->seconds(false),

                                        Group::make([
                                            Textarea::make('description')
                                                ->label('mycms::resources/page.fields.description.label')
                                                ->translateLabel()
                                                ->helperText(__('mycms::resources/page.fields.description.helper-text'))
                                                ->columnSpan(2),
                                            // here we can add further SEO fields
                                        ])
                                            ->afterStateHydrated(function (Group $component, ?Page $record): void {
                                                $component->getChildComponentContainer()->fill(
                                                    $record?->seo?->only('description') ?: []
                                                );
                                            })
                                            ->statePath('seo')
                                            ->dehydrated(false)
                                            ->saveRelationshipsUsing(function (Page $record, array $state): void {
                                                $state = collect($state)->only(['description'])->map(fn ($value) => $value ?: null)->all();

                                                if ($record->seo->exists) {
                                                    $record->seo->update($state);
                                                } else {
                                                    $record->seo()->create($state);
                                                }
                                            }),
                                    ]),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('mycms::resources/page.table.columns.id')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('mycms::resources/page.table.columns.title')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('mycms::resources/page.table.columns.slug')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('mycms::resources/page.table.columns.author')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('mycms::resources/page.table.actions.edit')
                    ->translateLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('mycms::resources/page.table.bulk-actions.delete')
                        ->translateLabel(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            HomePageWidget::class,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('mycms::resources/page.navigation-group');
    }

    public static function getNavigationIcon(): string
    {
        return __('mycms::resources/page.navigation-icon');
    }

    public static function getNavigationLabel(): string
    {
        return __('mycms::resources/page.navigation-label');
    }

    public static function getLabel(): string
    {
        return self::getNavigationLabel();
    }
}
