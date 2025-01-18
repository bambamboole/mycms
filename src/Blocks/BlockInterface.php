<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Blocks;

use Filament\Forms\Components\Builder\Block;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

interface BlockInterface
{
    public function id(): string;

    public function getBlock(): Block;

    public function render(array $data): string|View|Htmlable;
}