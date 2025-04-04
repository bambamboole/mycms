<?php
declare(strict_types=1);

namespace Bambamboole\MyCms\Resources\MenuResource\Pages;

use Bambamboole\MyCms\Resources\MenuResource;
use Bambamboole\MyCms\Resources\MenuResource\HasLocationAction;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    use HasLocationAction;

    protected static string $view = 'mycms::menu.edit-record';

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
