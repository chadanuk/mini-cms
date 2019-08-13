<?php

namespace Chadanuk\MiniCms\Blocks;

use Chadanuk\MiniCms\Blocks\Block;
use Illuminate\Support\Facades\View;
use Chadanuk\MiniCms\Blocks\BlockContent;

abstract class BlockTypeAbstract
{
    protected static $blockType = 'markdown';

    public $block;
    public $blockContent;
    public $pageId;

    public function __construct(Block $block, BlockContent $blockContent, int $pageId = null)
    {
        $this->block = $block;
        $this->blockContent = $blockContent;
        $this->pageId = $pageId;

        return $this;
    }

    abstract public function render();

    public function renderInput()
    {
        if (View::exists('mini-cms.admin.blocks.' . static::$blockType)) {
            return View::make(View::exists('mini-cms.admin.blocks.' . static::$blockType, ['block' => $this]));
        }

        return View::make('mini-cms::admin.blocks.' . static::$blockType, ['block' => $this]);
    }
}
