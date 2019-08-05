<?php

namespace Chadanuk\MiniCms\Contracts;

use Chadanuk\MiniCms\Blocks\BlockTypeAbstract;

interface BlockTypeContract
{
    public static function create($content, String $label = null, int $page_id = null): BlockTypeAbstract;
}
