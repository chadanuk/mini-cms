<?php

namespace Chadanuk\MiniCms\Tests\Unit;

use Chadanuk\MiniCms\MiniCms;
use Chadanuk\MiniCms\Tests\TestCase;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\ViewServiceProvider;
use Chadanuk\MiniCms\MiniCmsServiceProvider;
use Chadanuk\MiniCms\MiniCmsRouteServiceProvider;

class MiniCmsTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            MiniCmsServiceProvider::class,
            MiniCmsRouteServiceProvider::class
            // MiniCmsAdminRouteServiceProvider::class,
        ];
    }

    /**
     * @test
     */
    public function can_retrieve_admin_view_for_custom_placement()
    {
        $this->app['router']->get(config('mini-cms.admin-path') . '/mini-cms/{miniCmsPath}', function () {

            return '<h1>Custom Admin Panel</h1>' . \MiniCms::renderAdmin();
        })->where('miniCmsPath', '.*');

        $this->response = $this->withoutExceptionHandling()->get(config('mini-cms.admin-path') . '/mini-cms/pages/create');

        $this->assertContains('Create a page', $this->response->__toString());
        $this->assertContains('Custom Admin Panel', $this->response->__toString());
    }

    /**
     * @test
     */
    public function can_render_block_using_render_block()
    {
        View::addLocation(__DIR__ . '/../stubs/views');
        $page = \MiniCms::createPage(['name' => 'page1']);
        $page->fetchBlocks();

        $page = $page->fresh();
        $blockContent = $page->blockContents->get(0);
        $blockContent->update(['content' => 'Page title']);

        $blockContent = $page->blockContents->get(1);
        $blockContent->update(['content' => '## Second title']);

        $this->response = $this->withoutExceptionHandling()->get('page1');

        $this->assertContains('Page title', $this->response->__toString());
        $this->assertContains('<h2>Second title</h2>', $this->response->__toString());
    }

    /**
     * @test
     */
    public function can_render_content_for_block_by_defining_page_slug()
    {
        $page = \MiniCms::createPage(['name' => 'Terms']);

        $otherPage = \MiniCms::createPage(['name' => 'Other']);
        $otherPage->addBlock('string', 'Other title', 'A new piece of content');

        $this->assertEquals('A new piece of content', \MiniCms::getBlockOutput('string', 'Other title', 'other'));
    }

    /**
     * @test
     */
    public function non_existinent_block_renders_empty_string()
    {
        $page = \MiniCms::createPage(['name' => 'Terms']);

        $this->assertEquals('', \MiniCms::getBlockOutput('string', 'Title', $page->slug));
    }
}
