<?php

namespace App\ViewBlock\Frontend;

use App\Models\Paper;
use Illuminate\Contracts\Support\Htmlable;

class Message implements Htmlable
{
	protected $template = "frontend.templates.pageBlock.message";

	function toHtml(): string
	{
		return view($this->template)->render();
	}
}
