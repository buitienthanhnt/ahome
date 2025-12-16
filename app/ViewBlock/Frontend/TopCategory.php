<?php

namespace App\ViewBlock\Frontend;

use App\Enums\CacheStorage;
use App\Models\Category;
use App\Models\ConfigCategory;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\View;
use Thanhnt\Nan\Helper\CacheManager;

class TopCategory implements Htmlable
{
    protected $template = 'frontend.templates.pageBlock.topCategory';
    protected $cacheManager;

    function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    function toHtml()
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::BLOCK_TOP_CATEGORY,
            function () {
                $_topcategory = [];
                try {
                    $topcategory = ConfigCategory::where(ConfigCategory::ATTR_PATH, ConfigCategory::TOP_CATEGORY)->firstOr(function () {
                        return null;
                    });
                    if ($topcategory) {
                        $_topcategory = Category::find(explode("&", $topcategory->value));
                    }
                    return View::make($this->template)->with('topcategory', $_topcategory)->render();
                } catch (\Exception $e) {
                    return '';
                }
            },
            CacheStorage::TIME_2_D
        );
    }

    function setTemplate(string $template): TopCategory
    {
        $this->template = $template;
        return $this;
    }
}
