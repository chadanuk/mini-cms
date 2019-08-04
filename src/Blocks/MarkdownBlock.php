<?php

namespace Chadanuk\MiniCms\Blocks;

use Illuminate\Mail\Markdown;
use Chadanuk\MiniCms\Blocks\BlockTypeAbstract;
use Chadanuk\MiniCms\Contracts\BlockTypeContract;
use Chadanuk\MiniCms\Blocks\CreatesBlockFromContent;

class MarkdownBlock extends BlockTypeAbstract implements BlockTypeContract
{
    use CreatesBlockFromContent;

    protected static $blockType = 'markdown';

    public function render()
    {
        return Markdown::parse($this->content);
    }
}
