<?php

namespace Bambamboole\MyCms\Filament\Resources\PostResource\Pages;

use Bambamboole\MyCms\Filament\Resources\HasPreviewModal;
use Bambamboole\MyCms\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Mansoor\FilamentVersionable\Page\RevisionsAction;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;

class EditPost extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            RevisionsAction::make(),
            PreviewAction::make(),
        ];
    }
}
