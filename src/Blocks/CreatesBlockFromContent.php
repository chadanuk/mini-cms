<?php

namespace Chadanuk\MiniCms\Blocks;

use Chadanuk\MiniCms\Blocks\Block;
use Chadanuk\MiniCms\Blocks\BlockContent;
use Chadanuk\MiniCms\Blocks\BlockTypeAbstract;

trait CreatesBlockFromContent
{
    public static function create($content, String $label = null, int $page_id = null): BlockTypeAbstract
    {
        $blockClass = \get_class();

        $block = Block::firstOrCreate(['type' => self::$blockType]);

        $content = BlockContent::create([
            'block_id' => $block->id,
            'page_id' => $page_id,
            'content' => $content,
        ]);

        return new $blockClass($content->block_id, $content->id, $label, $page_id, $content);
    }
}
