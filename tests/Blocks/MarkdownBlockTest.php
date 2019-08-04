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
            'content' => "# Title\nSome content goes here."
        ]);

        $this->assertDatabaseHas('blocks', [
            'type' => 'markdown',
        ]);

        $block = Block::first();

        $this->assertDatabaseHas('block_contents', [
            'block_id' => $block->id,
            'content' => "# Title\nSome content goes here."
        ]);
    }

    /**
     * @test
     */
    public function rendering_a_markdown_block_formats_it_as_html()
    {
        $markdownBlock = MarkdownBlock::create([
            'content' => "# Title\nSome content goes here."
        ]);

        $output = $markdownBlock->render()->__toString();

        $this->assertContains('<h1>Title</h1>', $output);
        $this->assertContains('<p>Some content goes here.</p>', $output);
    }
}
