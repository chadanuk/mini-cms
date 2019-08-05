<?php

namespace Chadanuk\MiniCms\Blocks;

use Chadanuk\MiniCms\Blocks\BlockContent;

class BlockFactory
{

    public static function create(Block $block, BlockContent $content, String $label, $page_id)
    {
        $blockClass = '\\Chadanuk\MiniCms\Blocks\\' . ucfirst($block->type) . 'Block';

        return new $blockClass($block->id, $blockContent->->id, $label, $page_id, $blockContent->content);
    }
}
