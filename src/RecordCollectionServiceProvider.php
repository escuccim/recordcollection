<?php

namespace Escuccim\RecordCollection;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class RecordCollectionServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // use this if your package has views
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'records');

        // use this if your package has lang files
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'record-lang');

        // use this if your package has routes
        $this->setupRoutes($this->app->router);

        // load our migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // publish config if necessary
        $this->publishes([
            __DIR__.'/config/records.php' => config_path('records.php'),
            __DIR__.'/resources/views/pagination' => base_path('resources/views/vendor/pagination'),
            __DIR__.'/assets/recordsearch.js' => base_path('public/js/recordsearch.js'),
            __DIR__.'/assets/fonts' => base_path('public/fonts')
        ], 'config');

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/resources/lang' => base_path('resources/lang/vendor/record-lang')
        ], 'lang');

        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/vendor/records')
        ], 'views');

        // use the default configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__.'/config/records.php', 'records'
        );
    }
    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Escuccim\RecordCollection\Http\Controllers'], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerClass();

        // specify the config file
        config([
            'config/records.php',
        ]);
    }
    private function registerClass()
    {
        $this->app->bind('escuccim',function($app){
            return new RecordClass($app);
        });
    }
}