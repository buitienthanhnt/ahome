<?php

namespace App\ViewBlock\Frontend;

use Illuminate\Contracts\Support\Htmlable;

class Social implements Htmlable
{
    protected $template = "frontend.templates.pageBlock.social";

    function toHtml(): string
    {
        $listSocials = [
            [
                'url' => 'https://www.facebook.com/profile.php?id=61562482551696',
                'image' => 'assets/frontend/img/news/icon-fb.png',
                'type' => '',
                'count' => number_format(1234)],
            [
                'url' => 'https://zalo.me/0702032201',
                'image' => 'assets/frontend/img/news/zalo.png',
                'type' => '',
                'count' => number_format(2222)],
            [
                'url' => 'https://t.me/thanh_nt_b',
                'image' => 'assets/frontend/img/news/tele.png',
                'type' => '',
                'count' => number_format(3334)],
            [
                'url' => 'https://www.youtube.com/channel/UCgLNTcps0zAPfaz74YGsplA',
                'image' => 'assets/frontend/img/news/icon-yo.png',
                'type' => '',
                'count' => number_format(4566)
            ],
        ];
        return view($this->template, ["listSocials" => $listSocials])->render();
    }
}
