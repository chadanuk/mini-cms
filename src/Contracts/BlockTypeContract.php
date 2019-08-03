<?php

namespace Chadanuk\MiniCms\Contracts;

use Chadanuk\MiniCms\Blocks\BlockContent;

interface BlockTypeContract
{
    public static function create(array $data): BlockContent;
}
