<?php

namespace Dluwang\Auth\Providers;

use Dluwang\Auth\Console\Commands\InstallCommand;
use Dluwang\Auth\Console\Commands\CollectPermission;
use Illuminate\Support\ServiceProvider;
use Dluwang\Auth\Services\PolicyTransformer\Contract as PolicyTransformerContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return  void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/auth.php', 'dluwang-auth'
        );

        $this->app->bind(PolicyTransformerContract::class, function($app){
            $policyTransformer = $app->config->get('dluwang-auth.policy.transformer');

            return $app->make($policyTransformer);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return  void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->publishes([
            __DIR__.'/../../config/auth.php' => config_path('dluwang-auth.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                CollectPermission::class,
            ]);
        }
    }
}
