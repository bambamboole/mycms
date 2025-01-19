<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Workbench\App\Models\User;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        config()->set('mycms.models.user', User::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {}
}
