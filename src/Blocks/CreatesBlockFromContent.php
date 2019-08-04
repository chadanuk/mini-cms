<?php

namespace Chadanuk\MiniCms\Blocks;

use Chadanuk\MiniCms\Blocks\Block;
use Chadanuk\MiniCms\Blocks\BlockContent;
use Chadanuk\MiniCms\Blocks\BlockTypeAbstract;

trait CreatesBlockFromContent
{
    public static function create(array $data): BlockTypeAbstract
    {
        $blockClass = \get_class();

        $block = Block::firstOrCreate(['type' => self::$blockType]);

        $content = BlockContent::create([
            'block_id' => $block->id,
            'content' => $data['content'],
        ]);

        return new $blockClass($content->block_id, $content->content);
    }
}
