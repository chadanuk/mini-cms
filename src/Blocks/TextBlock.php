<?php

namespace Chadanuk\MiniCms\Blocks;

use Illuminate\Mail\Markdown;
use Chadanuk\MiniCms\Blocks\BlockTypeAbstract;
use Chadanuk\MiniCms\Contracts\BlockTypeContract;
use Chadanuk\MiniCms\Blocks\CreatesBlockFromContent;

class TextBlock extends BlockTypeAbstract implements BlockTypeContract
{
    use CreatesBlockFromContent;

    protected static $blockType = 'text';

    public function render()
    {
        return nl2br($this->blockContent->content);
    }
}
