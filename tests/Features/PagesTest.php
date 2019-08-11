<?php

namespace Chadanuk\MiniCms\Tests\Features;

use Chadanuk\MiniCms\Tests\TestCase;

class PagesTest extends TestCase
{

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

    /**
     * @test
     */
    public function home_page_can_be_visited_by_a_route()
    {
        $page = \MiniCms::createPage([
            'name' => 'Home',
            'slug' => 'home',
        ]);

        $this->response = $this->withoutExceptionHandling()->get('/');

        $this->response->assertStatus(200, $this->response->__toString());
        $this->response->assertViewHas('page', $page);
        $this->response->assertSee('Home');
    }
}
