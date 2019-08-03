<?php

namespace Chadanuk\MiniCms;

use Chadanuk\MiniCms\Page;

class MiniCms
{
    public function createPage(array $pageData = [])
    {
        return Page::create($pageData);
    }
}
