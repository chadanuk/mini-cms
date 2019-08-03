<?php

namespace Chadanuk\MiniCms\Tests\Blocks;

use Chadanuk\MiniCms\Blocks\Block;
use Chadanuk\MiniCms\Tests\TestCase;
use Chadanuk\MiniCms\Blocks\MarkdownBlock;
use Chadanuk\MiniCms\MiniCmsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarkdownBlockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_markdown_block_can_be_created()
    {
        $markdownBlock = MarkdownBlock::create([
            'content' => <<<EOD
            # Title
            Some content goes here.
EOD
        ]);

        $this->assertDatabaseHas('blocks', [
            'type' => 'markdown',
        ]);

        $block = Block::first();

        $this->assertDatabaseHas('block_contents', [
            'block_id' => $block->id,
            'content' => <<<EOD
            # Title
            Some content goes here.
EOD
        ]);
    }
}
