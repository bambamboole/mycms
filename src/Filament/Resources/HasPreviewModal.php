<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Resources;

use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal as BaseHasPreviewModal;

trait HasPreviewModal
{
    use BaseHasPreviewModal;

    protected function getPreviewModalView(): ?string
    {
        return 'mycms::preview';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'post';
    }
}
