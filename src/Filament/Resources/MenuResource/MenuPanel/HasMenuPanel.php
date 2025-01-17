<?php

declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Resources\MenuResource\MenuPanel;

use Illuminate\Database\Eloquent\Builder;

trait HasMenuPanel
{
    public function getMenuPanelName(): string
    {
        return str($this->getTable())
            ->title()
            ->replace('_', ' ')
            ->toString();
    }

    public function getMenuPanelModifyQueryUsing(): callable
    {
        return fn (Builder $query) => $query;
    }
}
