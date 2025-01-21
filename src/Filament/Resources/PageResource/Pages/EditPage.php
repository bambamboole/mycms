<?php

namespace Bambamboole\MyCms\Filament\Resources\PageResource\Pages;

use Bambamboole\MyCms\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
