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
}
