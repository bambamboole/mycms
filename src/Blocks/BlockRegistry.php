<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Blocks;

use Illuminate\Support\Collection;

class BlockRegistry
{
    public function __construct(protected Collection $blocks = new Collection) {}

    public function register(BlockInterface $block): self
    {
        $this->blocks->put($block->id(), $block);

        return $this;
    }

    public function all(): array
    {
        return $this->blocks->map(fn (BlockInterface $block) => $block->getBlock())->toArray();
    }

    public function raw(): array
    {
        return $this->blocks->toArray();
    }

    public function getBlockIds(): array
    {
        return $this->blocks->keys()->toArray();
    }

    public function getById(string $id): ?BlockInterface
    {
        return $this->blocks->first(fn (BlockInterface $block) => $block->id() === $id);
    }
}
