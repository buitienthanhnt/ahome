<?php

namespace App\ViewBlock;

use App\Models\User;
use App\Models\ViewSource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

final class AdminTop implements Htmlable
{
    protected $template = 'adminhtml.templates.share.adminTop';

    function __construct()
    {
    }

    function toHtml(): string
    {
        $list_data = [
            "user" => Session::get("admin_user", null)[0] ?? null
        ];
        return view($this->template, ['list_data' => $list_data])->render();
    }
}
