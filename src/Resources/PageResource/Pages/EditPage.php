<?php

namespace Bambamboole\MyCms\Resources\PageResource\Pages;

use Bambamboole\MyCms\Resources\HasPreviewModal;
use Bambamboole\MyCms\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;

class EditPage extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            PreviewAction::make(),
        ];
    }
}
