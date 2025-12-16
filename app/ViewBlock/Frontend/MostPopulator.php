<?php
namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use App\Models\ViewSource;
use App\Models\ViewSourceInterface;
use Illuminate\Contracts\Support\Htmlable;

class MostPopulator implements Htmlable
{
    protected $template = 'frontend.templates.pageBlock.mostPopulator';

    function toHtml(): string
    {
        try {
            $mostView = ViewSource::where(ViewSourceInterface::ATTR_TYPE, ViewSource::TYPE_PAPER)
                                  ->orderBy(ViewSourceInterface::ATTR_VALUE, 'desc')
                                  ->limit(8)
                                  ->pluck(ViewSourceInterface::ATTR_SOURCE_ID)
                                  ->toArray();
            return count($mostView) ?
                view($this->template, ['most_popular' => Paper::with('joinWriter')->find($mostView), 'title' => 'Phổ biến'])->render() :
                '';
        } catch (\Throwable $th) {}
        return '';
    }

    // public static function __callStatic($methodName, $arguments)
    // {
    // 	return static::connection()->$methodName(...$arguments);
    // }
}
