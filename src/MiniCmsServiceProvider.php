<?php

namespace Chadanuk\MiniCms;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Chadanuk\MiniCms\Events\PageCreating;
use Chadanuk\MiniCms\Listeners\CheckPageSlug;

class MiniCmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mini-cms');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'mini-cms');

        $this->publishes([
            __DIR__ . '/../database/migrations/create_blocks_table.php',
            database_path('migrations/' . date('Y-m-d_His') . 'create_blocks_table.php')
        ]);
        $this->publishes([
            __DIR__ . '/../database/migrations/create_block_contents_table.php',
            database_path('migrations/' . date('Y-m-d_His') . 'create_block_contents_table.php')
        ]);
        $this->publishes([
            __DIR__ . '/../database/migrations/create_pages_table.php',
            database_path('migrations/' . date('Y-m-d_His') . 'create_pages_table.php')
        ]);

        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('mini-cms.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/mini-cms'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/mini-cms'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/mini-cms'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'mini-cms');

        // Register the main class to use with the facade
        $this->app->singleton('mini-cms', function () {
            return new MiniCms;
        });

        Event::listen(PageCreating::class, CheckPageSlug::class);
    }
}
