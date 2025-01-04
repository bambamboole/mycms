<?php

namespace Bambamboole\MyCms\Filament\Resources;

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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RalphJSmit\Filament\SEO\SEO;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()->columnSpan('full')
                    ->tabs([
                        Tab::make('Content')->schema([
                            Forms\Components\Section::make()->columnSpan(2)->schema([
                                TextInput::make('title')
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                        if (!$get('is_slug_changed_manually') && filled($state)) {
                                            $set('slug', Str::slug($state));
                                        }
                                    })
                                    ->live(debounce: 300)
                                    ->helperText(function (?string $state): string {
                                        return (string) Str::of(strlen($state))
                                            ->append(' / ')
                                            ->append(60 .' ')
                                            ->append(Str::of(__('filament-seo::translations.characters'))->lower());
                                    })
                                    ->required(),
                                Forms\Components\MarkdownEditor::make('content')
                                    ->required()
                                    ->fileAttachmentsDisk(config('media-library.disk_name')),
                            ]),
                            Forms\Components\Section::make()->columnStart(3)->columnSpan(2)->schema([
                                TextInput::make('slug')
                                    ->afterStateUpdated(function (Forms\Set $set) {
                                        $set('is_slug_changed_manually', true);
                                    })
                                    ->required(),
                                Hidden::make('is_slug_changed_manually')
                                    ->default(false)
                                    ->dehydrated(false),
                                Forms\Components\DateTimePicker::make('published_at')->seconds(false),
                            ]),
                        ]),
                        Tab::make('SEO')->schema([
                            Group::make([
                                Textarea::make('description')
                                    ->label('SEO Description')
                                    ->columnSpan(2),
                                // here we can add further SEO fields
                            ])
                                ->afterStateHydrated(function (Group $component, ?Model $record): void {
                                    $component->getChildComponentContainer()->fill(
                                        $record?->seo?->only('description') ?: []
                                    );
                                })
                                ->statePath('seo')
                                ->dehydrated(false)
                                ->saveRelationshipsUsing(function (Model $record, array $state): void {
                                    $state = collect($state)->only(['description'])->map(fn ($value) => $value ?: null)->all();

                                    if ($record->seo && $record->seo->exists) {
                                        $record->seo->update($state);
                                    } else {
                                        $record->seo()->create($state);
                                    }
                                }),
                        ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
}
