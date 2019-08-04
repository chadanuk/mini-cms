<?php

namespace Chadanuk\MiniCms;

use Chadanuk\MiniCms\Blocks\Block;
use Illuminate\Database\Eloquent\Model;
use Chadanuk\MiniCms\Blocks\BlockContent;
use Chadanuk\MiniCms\Events\PageCreating;

class Page extends Model
{
    protected $guarded = [];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => PageCreating::class,
    ];


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function addBlock(String $blockType, $content = null): BlockContent
    {
        $blockClass = '\\Chadanuk\MiniCms\Blocks\\' . ucfirst($blockType) . 'Block';

        $block = $blockClass::create(['content' => $content]);

        $this->blocks()->attach($block);

        return $block;
    }

    public function blocks()
    {
        return $this->belongsToMany(Block::class, 'pages_blocks');
    }
}
