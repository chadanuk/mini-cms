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

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value = 'home')
    {
        return $this->where('slug', $value)->first() ?? abort(404);
    }

    public static function findBySlug(String $slug)
    {
        return self::where('slug', $slug)->first();
    }

    public function addBlock(String $blockType, String $pageBlockLabel, $content = null): BlockTypeAbstract
    {
        $existingBlockContent = $this->blockContents()->whereHas('block', function ($query) use ($blockType, $pageBlockLabel) {
            return $query->where('type', $blockType)->where('label', $pageBlockLabel);
        })->first();

        if (!$existingBlockContent) {

            $blockClass = '\\Chadanuk\MiniCms\Blocks\\' . ucfirst($blockType) . 'Block';

            $block = $blockClass::create($content, $pageBlockLabel, $this->id);
            $this->blockContents()->save($block->blockContent);
        } else {

            $block = BlockFactory::create($existingBlockContent->block()->first(), $existingBlockContent, $pageBlockLabel, $this->id);
        }

        return $block;
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

        preg_match_all('/\@minicms\((.*)\)/', $templateContents, $blocks);
        foreach ($blocks[1] as $key => $value) {
            $blockArgs = explode(', ', $value);
            list($type, $label) = $blockArgs;
            $pageSlug = null;

            if (isset($blockArgs[2])) {
                $pageSlug = $blockArgs[2];
            }

            $this->addBlock(trim($type, '\''), trim($label, '\''), trim($pageSlug, '\''));
        }

        return $this->blockContents()->get();
    }

    public function pageBlocks()
    {
        $blockData = collect([]);
        $this->blockContents()->get()->each(function ($blockContent) use ($blockData) {
            $blockData->push(['blockContent' => $blockContent, 'block' => $blockContent->block]);
        });

        return $blockData->map(function ($blockDataItem) {

            return BlockFactory::create(
                $blockDataItem['block'],
                $blockDataItem['blockContent'],
                $blockDataItem['block']->label,
                $this->id
            );
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
