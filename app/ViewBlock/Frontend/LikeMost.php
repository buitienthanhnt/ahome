<?php

namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class LikeMost implements Htmlable
{
	protected $template = "frontend.templates.pageBlock.mostLike";

	function toHtml(): string
	{
//		$papers = Paper::with('joinWriter')->with('joinViewSource')->get();
//		$likes = $papers->random($papers->count() >= 6 ? 6 : $papers->count());
        $likes = Paper::inRandomOrder()->with('joinWriter')->with('joinViewSource')->limit(6)->get();
		return view($this->template, ['likes' => $likes, 'title' => 'Đề xuât'])->render();
	}
}
