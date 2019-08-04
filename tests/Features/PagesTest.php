<?php

namespace Chadanuk\MiniCms\Tests\Features;

use Chadanuk\MiniCms\Tests\TestCase;

class PagesTest extends TestCase
{

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

        $this->response = $this->get($page->slug);

        dd($this->response);
        $this->response->assertStatus(200);
        $this->response->assertViewHas('page', $page);
        $this->response->assertSee('A title');
    }
}
