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

        if (View::exists('mini-cms.templates.default')) {
            return View('mini-cms.templates.default', ['page' => $page]);
        }

        return View::make('mini-cms::templates.default', ['page' => $page]);
    }

    public function index()
    {
        $pages = Page::all();
        if (View::exists('mini-cms.admin.pages.list')) {
            return View('mini-cms.admin.pages.list', ['pages' => $pages]);
        }

        return View::make('mini-cms::admin.pages.list', ['pages' => $pages]);
    }

    public function create()
    {
        if (View::exists('mini-cms.admin.pages.create')) {
            return View('mini-cms.admin.pages.create');
        }

        return View::make('mini-cms::admin.pages.create');
    }

    public function store(Request $request)
    {
        \MiniCms::createPage([
            'name' => $request->get('name'),

        ]);

        return response('', 201);
    }
}
