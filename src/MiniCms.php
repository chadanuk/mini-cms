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

    public function renderBlock(String $type, String $label, int $pageId = null)
    {
        $request = request();
        $page = $pageId ? Page::find($pageId) : $request->route('page');

        if (!$this->pageBlocks) {
            $this->pageBlocks = $page->pageBlocks();
        }

        if ($this->pageBlocks->count() === 0) {
            return '';
        }

        echo $this->pageBlocks->where('label', $label)->where('type', $type)->first()->render();
    }
}
