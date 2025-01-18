<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Blocks;

use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;

class TextBlock extends BaseBlock
{
    public function id(): string
    {
        return 'text-block';
    }

    protected function fields(): array
    {
        return [
            TextInput::make('text'),
        ];
    }

    public function render(array $data): View
    {
        return \view('mycms::blocks.text-block', $data);
    }
}
