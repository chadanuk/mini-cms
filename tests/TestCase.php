<?php

namespace Chadanuk\MiniCms\Tests;

use DirectoryIterator;
use Illuminate\Support\Str;
use Chadanuk\MiniCms\MiniCmsFacade;
use Chadanuk\MiniCms\MiniCmsServiceProvider;
use Chadanuk\MiniCms\MiniCmsRouteServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Chadanuk\MiniCms\MiniCmsAdminRouteServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * Response obhect
     *
     * @var \Illuminate\Foundation\Testing\TestResponse
     */
    public $response;


    public function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    public function runMigrations()
    {
        $this->artisan('migrate')->run();

        $iterator = new DirectoryIterator(__DIR__ . '/../database/migrations');
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filename = $file->getFilename();
                $className = '\\' . str_replace('.php', '', ucfirst(Str::camel($filename)));

                require_once $file->getRealPath();
                (new $className())->up();
            }
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            MiniCmsServiceProvider::class,
            MiniCmsRouteServiceProvider::class,
            MiniCmsAdminRouteServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'MiniCms' => MiniCmsFacade::class,
        ];
    }
}
