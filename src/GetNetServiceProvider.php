<?php

namespace CloudFox\GetNet;

use CloudFox\GetNet\Console\GetStatementCommand;
use CloudFox\GetNet\Console\GetStatementForCompanyCommand;
use CloudFox\GetNet\Console\InstallCommand;
use CloudFox\GetNet\Console\TruncateGetNetStatementCommand;
use Illuminate\Support\ServiceProvider;

class GetNetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'getnet');
        
        if ($this->app->runningInConsole()) {
            
            $this->registerMigrations();
            
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'getnet-migrations');
            
            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/getnet'),
            ], 'getnet-views');
            
            $this->publishes([
                __DIR__ . '/../config/getnet.php' => config_path('getnet.php'),
            ], 'getnet-config');
            
            $this->commands([
                InstallCommand::class,
                GetStatementCommand::class,
                GetStatementForCompanyCommand::class,
                TruncateGetNetStatementCommand::class,
            ]);
        }
    }
    
    /**
     * Register Passport's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        //if (Passport::$runsMigrations && ! config('passport.client_uuids')) {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        //}
    }
}
