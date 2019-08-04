<?php

namespace Chadanuk\MiniCms\Events;

use Chadanuk\MiniCms\Page;

class PageCreating
{

    /**
     * Page object on event
     *
     * @var Chadanuk\MiniCms\Page
     */
    public $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }
}
