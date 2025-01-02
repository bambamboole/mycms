<?php

namespace Bambamboole\MyCms\Filament\Resources;

use Bambamboole\MyCms\Filament\Resources\PostResource\Pages;
use Bambamboole\MyCms\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->columnSpan(2)->schema([
                    TextInput::make('title')
                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                            if (! $get('is_slug_changed_manually') && filled($state)) {
                                $set('slug', Str::slug($state));
                            }
                        })
                        ->live(debounce: 300)
                        ->required(),
                    Forms\Components\Textarea::make('excerpt')->rows(3)
                        ->required(),
                    Forms\Components\MarkdownEditor::make('content')
                        ->required()
                        ->fileAttachmentsDisk(config('media-library.disk_name')),
                ]),
                Forms\Components\Section::make()->columnStart(3)->columnSpan(1)->schema([
                    TextInput::make('slug')
                        ->afterStateUpdated(function (Forms\Set $set) {
                            $set('is_slug_changed_manually', true);
                        })
                        ->required(),
                    Hidden::make('is_slug_changed_manually')
                        ->default(false)
                        ->dehydrated(false),
                    SpatieMediaLibraryFileUpload::make('Image')->collection('featured_image')->imageEditor(),
                    SpatieTagsInput::make('tags'),
                    Forms\Components\DateTimePicker::make('published_at')->seconds(false),
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
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'revisions' => Pages\PostRevisions::route('/{record}/revisions'),
        ];
    }
}
