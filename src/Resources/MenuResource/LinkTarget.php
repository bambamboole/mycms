<?php

declare(strict_types=1);

namespace Bambamboole\MyCms\Resources\MenuResource;

use Filament\Support\Contracts\HasLabel;

enum LinkTarget: string implements HasLabel
{
    case Self = '_self';

    case Blank = '_blank';

    case Parent = '_parent';

    case Top = '_top';

    public function getLabel(): string
    {
        return match ($this) {
            self::Self => __('mycms::menu.open_in.options.self'),
            self::Blank => __('mycms::menu.open_in.options.blank'),
            self::Parent => __('mycms::menu.open_in.options.parent'),
            self::Top => __('mycms::menu.open_in.options.top'),
        };
    }
}
