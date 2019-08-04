<?php

namespace Chadanuk\MiniCms\Tests\Features;

use Chadanuk\MiniCms\Page;
use Chadanuk\MiniCms\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PagesTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function can_view_create_page_form()
    {
        $this->response = $this->withoutExceptionHandling()->get(config('mini-cms.admin-path') . '/mini-cms/pages/create');

        $this->response->assertStatus(200);
        $this->response->assertSee('Create a page');
        $this->response->assertSee('Page name');
        $this->response->assertSee('name="name"');
        $this->response->assertSee('Create!');
        $this->response->assertSee('type="submit"');
    }

    /**
     * @test
     */
    public function can_store_a_new_page()
    {
        $this->response = $this->withoutExceptionHandling()->post(config('mini-cms.admin-path') . '/mini-cms/pages/store', [
            'name' => 'Page',
        ]);

        $this->response->assertStatus(201);
        $this->assertEquals(1, Page::count());
        $this->assertEquals('Page', Page::first()->name);
    }

    /**
     * @test
     */
    public function can_see_list_of_pages()
    {
        $page = \MiniCms::createPage([
            'name' => 'Page name',
        ]);
        $this->response = $this->withoutExceptionHandling()->get(config('mini-cms.admin-path') . '/mini-cms/pages');

        $this->response->assertStatus(200);

        $this->response->assertSee('Pages');
        $this->response->assertSee('Create page');
        $this->response->assertSee('Name');
        $this->response->assertSee('Page name');
        $this->response->assertSee('Delete page');
        $this->response->assertSee('Edit page');
    }

    /**
     * @test
     */
    public function can_create_a_page_with_minimal_data()
    {
        $page = \MiniCms::createPage([
            'name' => 'Title',
        ]);

        $this->assertEquals('Title', $page->name);

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'name' => 'Title',
        ]);
    }

    /**
     * @test
     */
    public function creating_a_page_with_no_slug_creates_a_slug()
    {
        $page = \MiniCms::createPage([
            'name' => 'A title',
        ]);

        $this->assertEquals('A title', $page->name);

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'name' => 'A title',
            'slug' => 'a-title',
        ]);
    }

    /**
     * @test
     */
    public function a_block_can_be_added_to_a_page()
    {
        $page = \MiniCms::createPage([
            'name' => 'A title',
        ]);

        $blockContents = $page->addBlock('string', 'The page name');

        $this->assertCount(1, $page->blocks()->get());
    }

    /**
     * @test
     */
    public function a_page_can_be_retrieved_by_its_slug()
    {
        $page = \MiniCms::createPage([
            'name' => 'A title',
            'slug' => 'the-page-slug',
        ]);

        $page = \MiniCms::getPageBySlug('the-page-slug');

        $this->assertEquals('A title', $page->name);
    }

    /**
     * @test
     */
    public function a_page_can_be_visited_by_a_route()
    {
        $page = \MiniCms::createPage([
            'name' => 'A title',
            'slug' => 'the-page-slug',
        ]);

        $this->response = $this->withoutExceptionHandling()->get($page->slug);

        $this->response->assertStatus(200, $this->response->__toString());
        $this->response->assertViewHas('page', $page);
        $this->response->assertSee('A title');
    }
}
