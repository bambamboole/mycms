<?php

namespace Bambamboole\MyCms\Tests;

use Bambamboole\MyCms\MyCmsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Workbench\App\Models\User;

#[WithMigration]
class DuskTestCase extends \Orchestra\Testbench\Dusk\TestCase
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
        $this->loadMigrationsFrom(
            __DIR__.'/../workbench/database/migrations'
        );
    }

    public function defineEnvironment($app)
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', __DIR__.'/../workbench/database/dusk.sqlite');
        config()->set('database.connections.sqlite.busy_timeout', 'milliseconds');
        config()->set('database.connections.sqlite.journal_mode', 'wal');
        config()->set('database.connections.sqlite.synchronous', 'NORMAL');
        config()->set('mycms.models.user', User::class);
    }
}
