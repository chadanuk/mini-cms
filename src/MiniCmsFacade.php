<?php

namespace Chadanuk\MiniCms;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Chadanuk\MiniCms\Skeleton\SkeletonClass
 */
class MiniCmsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mini-cms';
    }
}
