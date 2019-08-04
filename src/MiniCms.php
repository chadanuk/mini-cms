<?php

namespace Chadanuk\MiniCms;

use Chadanuk\MiniCms\Page;

class MiniCms
{
    public function createPage(array $pageData = []): Page
    {
        return Page::create($pageData);
    }

    public function getPageBySlug(string $slug): Page
    {
        return Page::where('slug', $slug)->first();
    }
}
