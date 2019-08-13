<?php

namespace Chadanuk\MiniCms\Blocks;

use Illuminate\Database\Eloquent\Model;
use Chadanuk\MiniCms\Blocks\BlockContent;

class Block extends Model
{
    protected $guarded = [];

    public function blockContents()
    {
        return $this->hasMany(BlockContent::class);
    }
}
