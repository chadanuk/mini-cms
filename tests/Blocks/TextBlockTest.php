<?php

namespace Chadanuk\MiniCms\Tests\Blocks;

use Chadanuk\MiniCms\Blocks\Block;
use Chadanuk\MiniCms\Tests\TestCase;
use Chadanuk\MiniCms\Blocks\TextBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TextBlockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_text_block_can_be_created()
    {
        $textBlock = TextBlock::create(
            "Some content goes here. With some / or many paragraphs\n\nand is displayed sensibly."
        );

        $this->assertDatabaseHas('blocks', [
            'type' => 'text',
        ]);

        $block = Block::first();

        $this->assertDatabaseHas('block_contents', [
            'block_id' => $block->id,
            'content' => "Some content goes here. With some / or many paragraphs\n\nand is displayed sensibly."
        ]);
    }

    /**
     * @test
     */
    public function rendering_a_text_block_formats_it_as_html()
    {
        $textBlock = TextBlock::create(
            "Some content goes here. With some / or many paragraphs\nand is displayed sensibly."
        );

        $output = $textBlock->render();

        $this->assertContains("Some content goes here. With some / or many paragraphs<br />\nand is displayed sensibly.", $output);
    }

    /**
     * @test
     */
    public function can_render_admin_input_for_text_block()
    {
        $page = \MiniCms::createPage(['name' => 'Other']);
        $page->addBlock('text', 'Other title', 'A new piece of content');
        $block = $page->pageBlocks()->first();

        $this->assertContains('<textarea name="blocks[1]', $block->renderInput()->render());
    }
}
