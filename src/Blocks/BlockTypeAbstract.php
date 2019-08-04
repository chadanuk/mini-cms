<?php

namespace Chadanuk\MiniCms\Blocks;

abstract class BlockTypeAbstract
{
    protected static $blockType = 'markdown';

    public $block_id;
    public $content;

    public function __construct(int $block_id, $content = null)
    {
        $this->block_id = $block_id;
        $this->content = $content;

        return $this;
    }

    abstract public function render();
}
