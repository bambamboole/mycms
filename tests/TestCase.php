<?php

namespace Bambamboole\MyCms\Tests;

use Bambamboole\MyCms\MyCmsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\App\Models\User;

#[WithMigration]
class TestCase extends Orchestra
{
    use RefreshDatabase, WithWorkbench;

    protected $enablesPackageDiscoveries = true;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Bambamboole\\MyCms\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            MyCmsServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../workbench/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/../workbench/database/settings');
    }

    public function defineEnvironment($app)
    {
        config()->set('database.default', 'testing');
        config()->set('mycms.models.user', User::class);
    }
}
