<?php

namespace Bambamboole\MyCms\Commands;

use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    public $signature = 'mycms:update';

    public $description = 'This command updates MyCMS';

    public function handle(): int
    {
        $this->call('mycms:publish');

        $this->comment('All done');

        return self::SUCCESS;
    }
}
