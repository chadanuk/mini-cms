<?php

namespace Chadanuk\MiniCms\Blocks;

use Illuminate\Support\Facades\View;

abstract class BlockTypeAbstract
{
    protected static $blockType = 'markdown';

    public $blockId;
    public $blockContentId;
    public $pageId;
    public $content;
    public $label;

    public function __construct(int $blockId, int $blockContentId, String $label = null, int $pageId = null, $content = null)
    {
        $this->blockId = $blockId;
        $this->blockContentId = $blockContentId;
        $this->pageId = $pageId;
        $this->content = $content;
        $this->label = $label;

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
