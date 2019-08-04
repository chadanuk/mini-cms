<?php

namespace Chadanuk\MiniCms\Http\Controllers;

use Chadanuk\MiniCms\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PagesController
{
    public function show(Page $page)
    {
        if (View::exists('mini-cms.templates.' . $page->slug)) {
            return View('mini-cms.templates.' . $page->slug, ['page' => $page]);
        }

        return View::make('mini-cms.templates.default');
    }
}
