<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Blocks;

use Filament\Forms\Components\Builder;

class BlockBuilder extends Builder
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->blocks(
            $this->registry()
                ->all()
                ->map(fn (BlockInterface $block) => $block->getBlock())
                ->toArray(),
        );

        $this->mutateDehydratedStateUsing(function (?array $state): array {
            if (!is_array($state)) {
                return [];
            }

            $registeredBlockIds = $this->registry()->getBlockIds();

            return collect($state)
                ->filter(fn (array $block) => $registeredBlockIds->contains($block['type']))
                ->values()
                ->toArray();
        });
    }

    protected function registry(): BlockRegistry
    {
        return app(BlockRegistry::class);
    }
}
