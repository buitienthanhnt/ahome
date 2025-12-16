<?php

namespace App\ViewBlock\Frontend;

use App\Enums\CacheStorage;
use App\Models\Category;
use Illuminate\Contracts\Support\Htmlable;
use Thanhnt\Nan\Helper\CacheManager;

class PageFoot implements Htmlable
{
    protected $template = 'frontend.templates.pageBlock.pageFoot';

    protected $category;
    protected $cacheManager;

    public function __construct(
        Category $category,
        CacheManager $cacheManager
    )
    {
        $this->category = $category;
        $this->cacheManager = $cacheManager;
    }

    function toHtml(): string
    {
        return $this->cacheManager->getAndCache(CacheStorage::BLOCK_FOOTER,
            function () {
                $category = $this->category->getcategoryTreeData();
                return view($this->template, ['category' => $category])->render();
            },
            CacheStorage::TIME_2_D
        );
    }
}
