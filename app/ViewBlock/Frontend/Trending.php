<?php
namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class Trending implements Htmlable
{
    protected $template = "frontend.templates.pageBlock.trending";

    function toHtml(): string
    {
        $papers = Paper::on()->join('writers', 'papers.writer', '=', 'writers.id')->select('papers.*', 'writers.name')->get();
        $trendings = $papers->random($papers->count() >= 5 ? 5 : $papers->count());
        return view($this->template, ['trendings' => $trendings, "title" => "Xu hướng"])->render();
    }
}
