<?php

namespace Chadanuk\MiniCms;

use Chadanuk\MiniCms\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

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

                return $route->matches($request) && $route->uri !== config('mini-cms.admin-path') . '/mini-cms/{miniCmsPath}';
            }
        );

        if ($routes->count() === 0) {
            return '';
        }

        $route = $routes->first();

        return $route->bind($request)->run();
    }
}
