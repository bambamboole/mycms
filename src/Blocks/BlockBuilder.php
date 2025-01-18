<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Blocks;

use Filament\Forms\Components\Builder;

class BlockBuilder extends Builder
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->blocks(app(BlockRegistry::class)->all());

        $this->mutateDehydratedStateUsing(static function (?array $state): array {
            if (!is_array($state)) {
                return [];
            }

            $registeredBlockIds = app(BlockRegistry::class)->getBlockIds();

            return collect($state)
                ->filter(fn (array $block) => in_array($block['type'], $registeredBlockIds, true))
                ->values()
                ->toArray();
        });
    }
}
