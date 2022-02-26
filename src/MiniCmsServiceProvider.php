<?php

namespace Chadanuk\MiniCms;

use Illuminate\Mail\Markdown;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Chadanuk\MiniCms\Events\PageCreating;
use Illuminate\View\Compilers\BladeCompiler;
use Chadanuk\MiniCms\Listeners\CheckPageSlug;

class MiniCmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Filesystem $filesystem)
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mini-cms');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'mini-cms');

        $this->publishes([
            __DIR__ . '/../database/migrations/create_blocks_table.php' =>
            $this->getMigrationFilename($filesystem, 'create_blocks_table.php'),
            __DIR__ . '/../database/migrations/create_block_contents_table.php' =>
            $this->getMigrationFilename($filesystem, 'create_block_contents_table.php'),
            __DIR__ . '/../database/migrations/create_pages_table.php' =>
            $this->getMigrationFilename($filesystem, 'create_pages_table.php'),
            __DIR__ . '/../database/migrations/create_pages_blocks_table.php' =>
            $this->getMigrationFilename($filesystem, 'create_pages_blocks_table.php'),
        ], 'migrations');

        // $this->loadRoutesFrom(__DIR__.'/routes.php');


        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('mini-cms.php'),
        ], 'config');

        // Publishing the views.
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/mini-cms'),
        ], 'mini-cms');


        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/mini-cms'),
        ], 'assets');*/


        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/mini-cms'),
        ], 'lang');*/
        if ($this->app->runningInConsole()) {
            // Registering package commands.
            // $this->commands([]);
        }
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mini-cms');
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
        $this->registerBladeExtensions();
    }

    public function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('minicms', function ($arguments) {
                list($type, $label) = explode(',', $arguments . ',');

                return "<?php \MiniCms::renderBlock($type, $label) ?>";
            });

            $bladeCompiler->directive('markdown', function ($arguments) {
                list($text) = explode(',', $arguments . ',');
                return Markdown::parse(($text));
            });
        });
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem, $filename): string
    {
        $timestamp = date('Y_m_d_His');
        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $filename) {
                return $filesystem->glob($path . $filename);
            })->push($this->app->databasePath() . "/migrations/{$timestamp}_{$filename}")
            ->first();
    }
}
