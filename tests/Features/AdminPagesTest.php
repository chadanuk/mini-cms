<?php

namespace Chadanuk\MiniCms\Tests\Features;

use Chadanuk\MiniCms\Page;
use Chadanuk\MiniCms\Tests\TestCase;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminPagesTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function can_view_create_page_form()
    {
        $this->response = $this->withoutExceptionHandling()->get(config('mini-cms.admin-path') . '/mini-cms/pages/create');

        $this->response->assertStatus(200);
        $this->response->assertSee('action="' . route('mini-cms.pages.store') . '"');
        $this->response->assertSee('method="post"');
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
        $this->response->assertSee('edit/' . $page->id);
        $this->response->assertSee('delete/' . $page->id);
        $this->response->assertSee('Page name');
        $this->response->assertSee('Delete page');
        $this->response->assertSee('Edit page');
    }

    /**
     * @test
     */
    public function page_form_populated_with_blocks_in_template()
    {
        View::addLocation(__DIR__ . '/../stubs/views');

        $page = \MiniCms::createPage([
            'name' => 'Page1',
        ]);

        $this->response = $this->withoutExceptionHandling()->get(config('mini-cms.admin-path') . '/mini-cms/pages/edit/' . $page->id);

        $blocks = $page->blocks()->withPivot('label')->get();

        $this->assertCount(2, $blocks);
        $this->assertEquals('Title', $blocks->get(0)->pivot->label);
        $this->assertEquals('Content', $blocks->get(1)->pivot->label);

        $this->response->assertStatus(200);
        $this->response->assertSee('Edit page');
        $this->response->assertSee('action="' . route('mini-cms.pages.update', ['id' => $page->id]) . '"');
        $this->response->assertSee('method="post"');
        $this->response->assertSee('Update page');
        $this->response->assertSee('Page1');
        $this->response->assertSee('name="name"');
        $this->response->assertSee('type="submit"');
        $this->response->assertSee('Title');
        $this->response->assertSee('Content');
        $this->response->assertSee('<input type="text" name="blocks[1]');
        $this->response->assertSee('<textarea name="blocks[2]');
    }

    /**
     * @test
     */
    public function can_update_block_contents()
    {
        View::addLocation(__DIR__ . '/../stubs/views');

        $page = \MiniCms::createPage([
            'name' => 'Page1',
        ]);
        $page->fetchBlocks();

        $this->response = $this->withoutExceptionHandling()->post(config('mini-cms.admin-path') . '/mini-cms/pages/update/' . $page->id, [
            'name' => 'Page',
            'blocks' => [
                1 => 'New Title',
                2 => '- Some list item\\n- Some second list item',
            ],
        ]);

        $this->response->assertStatus(302);

        $this->assertDatabaseHas('block_contents', ['block_id' => 1, 'page_id' => 1, 'content' => 'New Title']);
        $this->assertDatabaseHas('block_contents', ['block_id' => 2, 'page_id' => 1, 'content' => '- Some list item\\n- Some second list item']);
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
}
