<?php

namespace Bambamboole\MyCms\Resources\PageResource\Pages;

use Bambamboole\MyCms\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PageResource\Widgets\HomePageWidget::class,
        ];
    }
}
