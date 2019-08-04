<?php

namespace Chadanuk\MiniCms\Contracts;

use Chadanuk\MiniCms\Blocks\BlockTypeAbstract;

interface BlockTypeContract
{
    public static function create(array $data): BlockTypeAbstract;
}
