<?php

namespace Bambamboole\MyCms\Resources;

use Bambamboole\MyCms\Blocks\BlockBuilder;
use Bambamboole\MyCms\Facades\MyCms;
use Bambamboole\MyCms\Models\Post;
use Bambamboole\MyCms\Resources\PostResource\Pages;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->columnSpan('full')
                    ->tabs([
                        self::getContentTab(),
                    ]),

            ]);
    }

    public static function getContentTab(): Tab
    {
        return Tab::make('Content')
            ->columns(4)
            ->schema([
                Forms\Components\Section::make()
                    ->columnSpan(3)
                    ->schema([
                        TextInput::make('title')
                            ->label('mycms::resources/post.fields.title.label')
                            ->translateLabel()
                            ->helperText(__('mycms::resources/post.fields.title.helper-text'))
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                if (!$get('is_slug_changed_manually') && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->live(debounce: 300)
                            ->required(),
                        Forms\Components\Textarea::make('excerpt')
                            ->label('mycms::resources/post.fields.excerpt.label')
                            ->translateLabel()
                            ->helperText(__('mycms::resources/post.fields.excerpt.helper-text'))
                            ->rows(3),
                        BlockBuilder::make('blocks'),
                    ]),
                Forms\Components\Section::make()
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('slug')
                            ->label('mycms::resources/post.fields.slug.label')
                            ->translateLabel()
                            ->helperText(__('mycms::resources/post.fields.slug.helper-text'))
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('is_slug_changed_manually', true);
                            })
                            ->required(),
                        Hidden::make('is_slug_changed_manually')
                            ->default(false)
                            ->dehydrated(false),
                        SpatieMediaLibraryFileUpload::make('Image')
                            ->label('mycms::resources/post.fields.image.label')
                            ->translateLabel()
                            ->helperText(__('mycms::resources/post.fields.image.helper-text'))
                            ->collection('featured_image')
                            ->imageEditor(),
                        SpatieTagsInput::make('tags')
                            ->label('mycms::resources/post.fields.tags.label')
                            ->translateLabel()
                            ->helperText(__('mycms::resources/post.fields.tags.helper-text')),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('mycms::resources/post.fields.published_at.label')
                            ->translateLabel()
                            ->helperText(__('mycms::resources/post.fields.published_at.helper-text'))
                            ->seconds(false),
                        Forms\Components\Select::make('layout')
                            ->label(__('mycms::resources/page.fields.layout.label'))
                            ->helperText(__('mycms::resources/page.fields.layout.helper-text'))
                            ->options(array_keys(MyCms::getLayouts())),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('mycms::resources/post.table.columns.id')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('mycms::resources/post.table.columns.title')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('mycms::resources/post.table.columns.author')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('mycms::resources/post.table.actions.edit')
                    ->translateLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('mycms::resources/post.table.bulk-actions.delete')
                        ->translateLabel(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'revisions' => Pages\PostRevisions::route('/{record}/revisions'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('mycms::resources/post.navigation-group');
    }

    public static function getNavigationIcon(): string
    {
        return __('mycms::resources/post.navigation-icon');
    }

    public static function getNavigationLabel(): string
    {
        return __('mycms::resources/post.navigation-label');
    }

    public static function getLabel(): string
    {
        return self::getNavigationLabel();
    }
}
