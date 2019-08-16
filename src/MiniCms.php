<?php

namespace Chadanuk\MiniCms;

use Chadanuk\MiniCms\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class MiniCms
{
    public $pageBlocks = null;

    public function createPage(array $pageData = []): Page
    {
        return Page::create($pageData);
    }

    public function getPageBySlug(string $slug): Page
    {
        return Page::where('slug', $slug)->first();
    }

    public function renderAdmin()
    {
        $request = request();

        if (!$request->is(config('mini-cms.admin-path') . '/*')) {
            return response('', 404);
        }

        $adminRouteServiceProvider = new MiniCmsAdminRouteServiceProvider(app());
        $adminRouteServiceProvider->boot();
        $response = null;

        $routes = collect(app()->routes->getRoutes())->filter(
            function (Route $route) use ($request, $response) {

                return is_string($route->action['uses']) && stristr($route->action['uses'], 'Chadanuk\MiniCms') !== false && $route->matches($request) && $route->uri !== config('mini-cms.admin-path') . '/mini-cms/{miniCmsPath}';
            }
        );

        if ($routes->count() === 0) {
            return '';
        }

        $route = $routes->first();

        return $route->bind($request)->run();
    }

    public function getBlockOutput(String $type, String $label, string $pageSlug = null)
    {
        $request = request();
        $page = $pageSlug ? Page::findBySlug($pageSlug) : $request->route('page');
        if (!$page && $request->is('/')) {
            $page = Page::findBySlug(('home'));
        }

        if (!$this->pageBlocks) {
            $this->pageBlocks = $page->pageBlocks();
        }

        if ($this->pageBlocks->count() === 0) {
            return '';
        }
        $block = $this->pageBlocks->where('block.label', $label)->where('block.type', $type)->first();
        return $block ? $block->render() : '';
    }

    public function renderBlock(String $type, String $label, string $pageSlug = null)
    {
        echo static::getBlockOutput($type, $label, $pageSlug);
    }
}
