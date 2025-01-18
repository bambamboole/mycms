<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Blocks;

use Bambamboole\MyCms\Torchlight\TorchlightExtension;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use RyanChandler\CommonmarkBladeBlock\BladeExtension;

class MarkdownBlock extends BaseBlock
{
    public function id(): string
    {
        return 'markdown-block';
    }

    protected function fields(): array
    {
        return [
            MarkdownEditor::make('content'),
        ];
    }

    public function render(array $data): View
    {
        $extensions = [new BladeExtension, new AttributesExtension];
        if (config('torchlight.token') !== null) {
            $extensions[] = new TorchlightExtension;
        }

        $html = Str::markdown($data['content'], extensions: $extensions);

        return \view('mycms::blocks.markdown-block', ['content' => $html]);
    }
}
