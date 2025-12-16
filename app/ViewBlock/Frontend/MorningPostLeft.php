<?php

namespace App\ViewBlock\Frontend;

use App\Enums\CacheStorage;
use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;
use Thanhnt\Nan\Helper\CacheManager;

class MorningPostLeft implements Htmlable
{
    protected $template = "frontend.templates.pageBlock.morningPostLeft";
    protected $cacheManager;

    function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    function toHtml(): string
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::BLOCK_MORNING_TOP_LEFT,
            function () {
                try {
                    $trendingLeft = Paper::take(3)->orderBy('papers.created_at', "DESC")
                        ->join('writers', 'papers.writer', '=', 'writers.id')
                        ->select('papers.*', 'writers.name')
                        ->get();
                    return view($this->template, ['trending_left' => $trendingLeft])->render();
                } catch (\Throwable $th) {
                }
                return '';
            },
            CacheStorage::TIME_1_H,
        );
        // $trendingLeft = \DB::table('papers')->orderBy('papers.created_at', 'desc')->take(3)->join('writers', 'papers.writer', '=', 'writers.id')->get();
    }
}
