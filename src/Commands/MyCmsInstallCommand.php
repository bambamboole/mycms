<?php

namespace Bambamboole\MyCms\Commands;

use Illuminate\Console\Command;

class MyCmsInstallCommand extends Command
{
    public $signature = 'mycms:install';

    public $description = 'This command installs mycms';

    public function handle(): int
    {
        // publish migrations form spatie/laravel-health
        $this->call('vendor:publish', ['--tag' => 'health-migrations']);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
