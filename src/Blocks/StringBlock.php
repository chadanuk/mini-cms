<?php

namespace Chadanuk\MiniCms\Blocks;

use Chadanuk\MiniCms\Blocks\BlockTypeAbstract;
use Chadanuk\MiniCms\Contracts\BlockTypeContract;
use Chadanuk\MiniCms\Blocks\CreatesBlockFromContent;

class StringBlock extends BlockTypeAbstract implements BlockTypeContract
{
    use CreatesBlockFromContent;

    protected static $blockType = 'string';
    protected $table = 'block_contents';

    public function render()
    {
        return $this->content;
    }
}
