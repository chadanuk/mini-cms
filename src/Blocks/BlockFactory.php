<?php

namespace Chadanuk\MiniCms\Blocks;

use Chadanuk\MiniCms\Blocks\BlockContent;

class BlockFactory
{

    public static function create(Block $block, BlockContent $blockContent, String $label, $page_id)
    {
        $blockClass = '\\Chadanuk\MiniCms\Blocks\\' . ucfirst($block->type) . 'Block';

        return new $blockClass($block->type, $block->id, $blockContent->id, $label, $page_id, $blockContent->content);
    }
}
