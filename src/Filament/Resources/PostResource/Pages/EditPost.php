<?php

namespace Bambamboole\MyCms\Filament\Resources\PostResource\Pages;

use Bambamboole\MyCms\Filament\Resources\PostResource;
use Bambamboole\MyCms\Models\Post;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Schema\ObjectSchema;
use EchoLabs\Prism\Schema\StringSchema;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use Mansoor\FilamentVersionable\Page\RevisionsAction;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            RevisionsAction::make(),
            Action::make('Improve Writing')
                ->icon('heroicon-m-pencil')
                ->color('info')
                ->form([
                    Textarea::make('excerpt')->rows(3)
                        ->required(),
                    MarkdownEditor::make('content')
                        ->required()
                        ->fileAttachmentsDisk(config('media-library.disk_name')),
                ])
                ->fillForm(function (Post $post) {
                    $schema = new ObjectSchema(
                        name: 'blog_post',
                        description: 'A structured blog post',
                        properties: [
                            new StringSchema('excerpt', 'A short excerpt from the blog post'),
                            new StringSchema('content', 'THe improved content of the blog post in markdown'),
                        ],
                        requiredFields: ['excerpt', 'content']
                    );

                    $response = Prism::structured()
                        ->using(Provider::OpenAI, 'gpt-4o-mini')
                        ->withSchema($schema)
                        ->withSystemPrompt(view('mycms::prompts.blog', ['post' => $post]))
                        ->withPrompt('Improve writing of the following blog post: '.$post->content)
                        ->generate();

                    return [
                        'excerpt' => $response->structured['excerpt'],
                        'content' => $response->structured['content'],
                    ];
                })
                ->action(function (array $data, Post $post) {
                    $post->update([
                        'excerpt' => $data['excerpt'],
                        'content' => $data['content'],
                    ]);
                    $this->redirect('/admin/posts/'.$post->id.'/edit');
                }),
        ];
    }
}
