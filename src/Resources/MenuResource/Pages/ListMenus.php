<?php
declare(strict_types=1);

namespace Bambamboole\MyCms\Resources\MenuResource\Pages;

use Bambamboole\MyCms\Resources\MenuResource;
use Bambamboole\MyCms\Resources\MenuResource\HasLocationAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenus extends ListRecords
{
    use HasLocationAction;

    public static function getResource(): string
    {
        return MenuResource::class;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            $this->getLocationAction(),
        ];
    }
}
