<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Blocks;

class BlockRenderer
{
    public function __construct(protected readonly BlockRegistry $blockRegistry) {}

    public function render(array $block)
    {
        $blockInstance = $this->blockRegistry->getById($block['type']);
        if (!$blockInstance) {
            return '';
        }

        return $blockInstance->render($block['data']);
    }
}
