<?php
namespace App\ViewBlock\Frontend;

use Illuminate\Contracts\Support\Htmlable;

class MorningPost implements Htmlable
{
    protected $template = "frontend.templates.pageBlock.morningPost";

    function toHtml(): string
    {
        return view($this->template)->render();
    }
}
