<?php

namespace Bambamboole\MyCms\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Workbench\App\Models\User;

use function Orchestra\Testbench\default_skeleton_path;

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

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../vendor/orchestra/testbench-core/laravel/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/../vendor/orchestra/testbench-core/laravel/database/settings');
    }

    public function defineEnvironment($app): void
    {
        $sqlitePath = default_skeleton_path().'/database/database.sqlite';
        if (!file_exists($sqlitePath)) {
            copy($sqlitePath.'.example', $sqlitePath);
        }
        config()->set('database.connections.sqlite.database', $sqlitePath);
        config()->set('database.connections.sqlite.synchronous', true);
        config()->set('mycms.models.user', User::class);
    }
}
