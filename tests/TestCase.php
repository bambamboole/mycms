<?php

namespace Bambamboole\MyCms\Tests;

use Bambamboole\MyCms\MyCmsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Health\HealthServiceProvider;
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
            HealthServiceProvider::class,
            LivewireServiceProvider::class,
            MyCmsServiceProvider::class,
        ];
    }

    public function defineEnvironment($app): void
    {
        config()->set('database.connections.sqlite.database', ':memory:');
        config()->set('mycms.models.user', User::class);
    }
}
