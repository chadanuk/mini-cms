<?php

namespace Chadanuk\MiniCms\Http\Controllers;

use Chadanuk\MiniCms\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PagesController
{
    public function show(Page $page, Request $request)
    {
        if ($request->is('/')) {
            $page = Page::where('slug', 'home')->first();
        }
        $viewPath = $page->getViewPath();

        return View::make($viewPath, ['page' => $page]);
    }

    public function index()
    {
        $pages = Page::all();
        if (View::exists('mini-cms.admin.pages.list')) {
            return View('mini-cms.admin.pages.list', ['pages' => $pages]);
        }

        return View::make('mini-cms::admin.pages.list', ['pages' => $pages]);
    }

    public function edit(Request $request, $id)
    {
        $page = Page::find($id);

        $page->fetchBlocks();

        if (View::exists('mini-cms.admin.pages.edit')) {
            return View('mini-cms.admin.pages.edit', ['page' => $page]);
        }

        return View::make('mini-cms::admin.pages.edit', ['page' => $page]);
    }

    public function update(Request $request, $id)
    {
        $page = Page::find($id);

        $page->update([
            'name' => $request->get('name', $page->name),
            'slug' => $request->get('slug', $page->slug),
        ]);

        $page->updateBlocks($request->get('blocks'));

        return redirect()->route('mini-cms.pages.edit', ['id' => $id])->with('success', true);
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
        $page = \MiniCms::createPage([
            'name' => $request->get('name'),

        ]);

        return redirect()->route('mini-cms.pages.edit', ['id' => $page->id])->with('success', true);
    }
}
