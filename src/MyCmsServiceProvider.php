<?php

namespace Bambamboole\MyCms;

use Illuminate\Support\Facades\Schedule;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Bambamboole\MyCms\Commands\MyCmsInstallCommand;

class MyCmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('mycms')
            ->hasConfigFile()
            ->hasViews()
//            ->hasMigration('create_mycms_table')
            ->hasCommand(MyCmsInstallCommand::class);

//        Schedule::command(RunHealthChecksCommand::class)
//            ->everyMinute();
    }
}
