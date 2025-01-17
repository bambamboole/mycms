<?php
declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Resources\MenuResource\Pages;

use Bambamboole\MyCms\Filament\Resources\MenuResource;
use Bambamboole\MyCms\Filament\Resources\MenuResource\HasLocationAction;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    use HasLocationAction;

    protected static string $view = 'filament-menu-builder::edit-record';

    public static function getResource(): string
    {
        return MenuResource::class;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema($form->getComponents()),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            $this->getLocationAction(),
        ];
    }
}
