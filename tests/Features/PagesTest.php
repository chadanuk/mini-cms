<?php

namespace Chadanuk\MiniCms\Tests\Features;

use Chadanuk\MiniCms\Page;
use Chadanuk\MiniCms\Tests\TestCase;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Testing\WithoutMiddleware;

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
}
