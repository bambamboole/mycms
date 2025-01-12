<?php

namespace Bambamboole\MyCms\Tests;

use Bambamboole\MyCms\MyCmsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Workbench\App\Models\User;

#[WithMigration]
class DuskTestCase extends \Orchestra\Testbench\Dusk\TestCase
{
    use RefreshDatabase, WithWorkbench;

    protected array $connectionsToTransact = [];

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
            LivewireServiceProvider::class,
            MyCmsServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../workbench/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/../workbench/database/settings');
    }

    public function defineEnvironment($app): void
    {
        config()->set('database.default', 'mysql');
        config()->set('database.connections.mysql.database', 'mycms_test');
        config()->set('database.connections.mysql.password', 'password');
        config()->set('database.connections.mysql.port', 3307);
        config()->set('mycms.models.user', User::class);
    }
}
