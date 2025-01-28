<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Blocks;

use Bambamboole\MyCms\Models\BasePostType;
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

    public function render(array $data, BasePostType $post): View
    {
        return \view('mycms::blocks.text-block', $data);
    }
}
