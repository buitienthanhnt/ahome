<?php
namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use App\Models\ViewSource;
use App\Models\ViewSourceInterface;
use Illuminate\Contracts\Support\Htmlable;

class Related implements Htmlable{
    protected $template = 'frontend.templates.pageBlock.related';
    function __construct()
    {
    }

    public function toHtml()
    {
        try {
            $mostView = ViewSource::where(ViewSourceInterface::ATTR_TYPE, ViewSource::TYPE_PAPER)
                ->orderBy(ViewSourceInterface::ATTR_VALUE, 'desc')
                ->limit(8)
                ->pluck(ViewSourceInterface::ATTR_SOURCE_ID)
                ->toArray();
            return count($mostView) ?
                view($this->template, ['relateds' => Paper::with("joinWriter")->find($mostView), 'title' => 'Tin liên quan'])->render() :
                '';
        } catch (\Throwable $th) {}
        return '';
    }
}
