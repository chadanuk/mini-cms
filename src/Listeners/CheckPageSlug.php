<?php

namespace Chadanuk\MiniCms\Listeners;

use Illuminate\Support\Str;
use Chadanuk\MiniCms\Events\PageCreating;

class CheckPageSlug
{
    public function handle(PageCreating $event)
    {
        if (!$event->page->slug) {
            $event->page->slug = Str::slug($event->page->name);
        }
    }
}
