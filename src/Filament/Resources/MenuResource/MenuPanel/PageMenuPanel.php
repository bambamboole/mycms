<?php
declare(strict_types=1);
namespace Bambamboole\MyCms\Filament\Resources\MenuResource\MenuPanel;

use Bambamboole\MyCms\Models\Page;

class PageMenuPanel extends AbstractMenuPanel
{
    public function getName(): string
    {
        return 'Pages';
    }

    public function getItems(): array
    {
        return Page::query()
            ->get()
            ->map(fn (Page $page) => [
                'title' => $page->title,
                'linkable_type' => $page::class,
                'linkable_id' => $page->id,
            ])
            ->all();
    }
}
