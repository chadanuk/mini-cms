<?php

namespace Chadanuk\MiniCms\Blocks;

use Chadanuk\MiniCms\Blocks\Block;
use Chadanuk\MiniCms\Blocks\BlockContent;

trait CreatesBlockFromContent
{
    public static function create(array $data): BlockContent
    {

        $block = Block::firstOrCreate(['type' => self::$blockType]);

        return BlockContent::create([
            'block_id' => $block->id,
            'content' => $data['content'],
        ]);
    }
}
