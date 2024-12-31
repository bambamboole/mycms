<?php

namespace Bambamboole\MyCms\Commands;

use Illuminate\Console\Command;

class MyCmsCommand extends Command
{
    public $signature = 'mycms';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
