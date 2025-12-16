<?php

namespace App\ViewBlock\Frontend;

use App\Enums\CacheStorage;
use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;
use Thanhnt\Nan\Helper\CacheManager;

class MostRecent implements Htmlable
{
    protected $template = 'frontend.templates.pageBlock.mostRecent';
    protected $cacheManager;

    function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    function toHtml(): string
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::BLOCK_MOST_RECENTS,
            function () {
                $mostRecents = Paper::limit(3)->with("joinViewSource")->with("joinWriter")->orderBy('created_at', 'DESC')->get();
                return view($this->template, ['most_recent' => $mostRecents, 'title' => 'Đề xuất'])->render();
            },
            CacheStorage::TIME_1_H
        );
    }
}
