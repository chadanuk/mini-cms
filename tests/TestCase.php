<?php

namespace Chadanuk\MiniCms\Tests;

use Chadanuk\MiniCms\MiniCmsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Chadanuk\MiniCms\MiniCmsFacade;

class TestCase extends BaseTestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    public function runMigrations()
    {
        $this->artisan('migrate')->run();

        require_once __DIR__ . '/../database/migrations/create_blocks_table.php';
        (new \CreateBlocksTable())->up();

        require_once __DIR__ . '/../database/migrations/create_block_contents_table.php';
        (new \CreateBlockContentsTable())->up();

        require_once __DIR__ . '/../database/migrations/create_pages_table.php';
        (new \CreatePagesTable())->up();
    }

    protected function getPackageProviders($app)
    {
        return [MiniCmsServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'MiniCms' => MiniCmsFacade::class,
        ];
    }
}
