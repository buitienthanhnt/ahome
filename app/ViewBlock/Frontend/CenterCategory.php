<?php

namespace App\ViewBlock\Frontend;

use App\Enums\CacheStorage;
use App\Models\Category;
use App\Models\ConfigCategory;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\View;
use Thanhnt\Nan\Helper\CacheManager;

class CenterCategory implements Htmlable
{
    protected $template = 'frontend.templates.pageBlock.centerCategory';
    protected $cacheManager;

    function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    function toHtml()
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::BLOCK_CENTER_CATEGORY,
            function () {
                $center_category = ConfigCategory::where("path", ConfigCategory::CENTER_CATEGORY)->firstOr(function () {
                    return null;
                });
                if ($center_category) {
                    $centerCategory = Category::find(explode("&", $center_category->value));
                    return View::make($this->template)->with('list_center', $centerCategory)->render();
                }
                return '';
            },
            CacheStorage::TIME_1_H
        );
    }

    function setTemplate(string $template)
    {
        $this->template = $template;
        return $this;
    }
}
