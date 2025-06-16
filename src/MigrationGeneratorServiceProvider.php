<?php

namespace Ganesh\MigrationGenerator;

use Illuminate\Support\ServiceProvider;
use Ganesh\MigrationGenerator\Commands\GenerateMigrationsCommand;

class MigrationGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/migration-generator.php', 'migration-generator');

        $this->publishes([
            __DIR__ . '/config/migration-generator.php' => config_path('migration-generator.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateMigrationsCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register bindings, if any
    }
}
