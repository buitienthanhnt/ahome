<?php

namespace App\ViewBlock\Frontend;

use App\Enums\CacheStorage;
use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;
use Thanhnt\Nan\Helper\CacheManager;

class MorningPostRight implements Htmlable
{
    protected $template = "frontend.templates.pageBlock.morningPostRight";
    protected $cacheManager;

    function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    function toHtml()
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::BLOCK_MORNING_TOP_RIGHT,
            function () {
                try {
                    $trendingRights = Paper::take(2)->offset(3)
                        ->orderBy("papers.created_at", "DESC")
                        ->join('writers', 'papers.writer', '=', 'writers.id')
                        ->select('papers.*', 'writers.name')
                        ->get();
                    return view($this->template, ['trending_right' => $trendingRights])->render();
                } catch (\Throwable $th) {
                }
                return '';
            },
            CacheStorage::TIME_1_H
        );
    }
}
