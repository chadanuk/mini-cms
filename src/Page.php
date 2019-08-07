<?php

namespace Chadanuk\MiniCms;

use Chadanuk\MiniCms\Blocks\Block;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;
use Chadanuk\MiniCms\Blocks\BlockContent;
use Chadanuk\MiniCms\Events\PageCreating;
use Chadanuk\MiniCms\Blocks\BlockTypeAbstract;
use Chadanuk\MiniCms\Blocks\BlockFactory;

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

    public function addBlock(String $blockType, String $pageBlockLabel, $content = null): BlockTypeAbstract
    {
        $existingBlock = $this->pageBlocks()->where('type', $blockType)->where('label', $pageBlockLabel)->first();
        if (!$existingBlock) {
            $blockClass = '\\Chadanuk\MiniCms\Blocks\\' . ucfirst($blockType) . 'Block';
            $block = $blockClass::create($content, $pageBlockLabel, $this->id);
            $this->blocks()->attach($block->blockId, ['label' => $pageBlockLabel]);
        } else {
            $block = BlockFactory::create(Block::find($existingBlock->blockId), BlockContent::find($existingBlock->blockContentId), $pageBlockLabel, $this->id);
        }

        return $block;
    }

    public function blocks()
    {
        return $this->belongsToMany(Block::class, 'pages_blocks');
    }

    public function blockContents()
    {
        return $this->hasMany(BlockContent::class);
    }

    public function getViewPath()
    {
        if (View::exists('mini-cms.templates.' . $this->slug)) {
            return 'mini-cms.templates.' . $this->slug;
        }

        if (View::exists('mini-cms::templates.' . $this->slug)) {
            return 'mini-cms::templates.' . $this->slug;
        }

        if (View::exists('mini-cms.templates.default')) {
            return 'mini-cms.templates.default';
        }

        return 'mini-cms::templates.default';
    }

    public function fetchBlocks()
    {
        $viewPath = $this->getViewPath();
        $fullPath = View::getFinder()->find($viewPath);

        $templateContents = file_get_contents($fullPath);

        preg_match_all('/\@minicms\(\'(.*)\', \'(.*)\'\)/', $templateContents, $blocks);

        foreach ($blocks[1] as $key => $type) {
            $this->addBlock($type, $blocks[2][$key], null);
        }

        return $this->blocks()->get();
    }

    public function pageBlocks()
    {
        $blockData = collect([]);
        $this->blocks()->withPivot('label')->get()->each(function ($block) use ($blockData) {
            $blockContents = BlockContent::where([
                'page_id' => $this->id,
                'block_id' => $block->id,
            ])->get();

            $blockContents->each(function ($blockContent) use ($blockData, $block) {
                $blockData->push(['blockContent' => $blockContent, 'block' => $block]);
            });
        });

        return $blockData->map(function ($blockDataItem) {
            return BlockFactory::create($blockDataItem['block'], $blockDataItem['blockContent'], $blockDataItem['block']->pivot->label, $this->id);
        });
    }

    public function updateBlocks(array $blockData = [])
    {
        collect($blockData)->each(function ($blockContent, $blockContentId) {

            $this->blockContents()->where([
                'id' => $blockContentId,
            ])->update(['content' => $blockContent]);
        });
    }
}
