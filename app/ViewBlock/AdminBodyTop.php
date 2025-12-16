<?php

namespace App\ViewBlock;

use App\Models\User;
use App\Models\ViewSource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class AdminBodyTop implements Htmlable
{
    protected $template = 'adminhtml.templates.share.bodyTopTab';

    function __construct()
    {
    }

    function toHtml(): string
    {
        return view($this->template)->render();
    }
}
