<?php

namespace App\ViewBlock;
use Illuminate\Contracts\Support\Htmlable;

final class AdminGlobalVar implements Htmlable
{
    protected $template = 'adminhtml.templates.share.AdminGlobalVar';

    function __construct(){}

    function toHtml(): string
    {
        // khai báo các biến javascript toàn cầu cho trang web.
        // từ đó các vị trí khác nhau đều truy cập được các biến này
        $vars = [
            'filemanager_url_base' => url("adminhtml/file/manager"),
            'filemanager_url_mce' => url("adminhtml/file/manager") . "?editor=tinymce5"
        ];
        return view($this->template, ['vars' => $vars])->render();
    }
}
