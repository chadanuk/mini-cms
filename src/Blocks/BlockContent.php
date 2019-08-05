<?php

namespace Chadanuk\MiniCms\Blocks;

use Chadanuk\MiniCms\Blocks\Block;
use Illuminate\Database\Eloquent\Model;

class BlockContent extends Model
{
    protected $guarded = [];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
