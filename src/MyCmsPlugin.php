<?php declare(strict_types=1);

namespace Bambamboole\MyCms;

use Filament\Contracts\Plugin;
use Filament\Panel;

class MyCmsPlugin implements Plugin
{

    public function getId(): string
    {
        return 'mycms';
    }

    public function register(Panel $panel): void
    {

    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
