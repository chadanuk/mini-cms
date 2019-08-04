<?php

namespace Chadanuk\MiniCms\Tests\Blocks;

use Chadanuk\MiniCms\Blocks\Block;
use Chadanuk\MiniCms\Tests\TestCase;
use Chadanuk\MiniCms\Blocks\StringBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StringBlockTest extends TestCase
{

    use RefreshDatabase;
    /**
     * @test
     */
    public function can_create_a_string_block()
    {
        StringBlock::create([
            'content' => 'A title',
        ]);

        $this->assertDatabaseHas('blocks', [
            'type' => 'string',
        ]);

        $block = Block::first();

        $this->assertDatabaseHas('block_contents', [
            'block_id' => $block->id,
            'content' => 'A title',
        ]);
    }

    /**
     * @test
     */
    public function can_grender_string_block()
    {
        $stringBlock = StringBlock::create([
            'content' => "a string"
        ]);

        $output = $stringBlock->render();

        $this->assertContains('a string', $output);
    }
}
