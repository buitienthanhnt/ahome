<?php

namespace Thanhnt\Ahomeglobal\Events;

use Thanhnt\Ahomeglobal\Models\Home;

final class HomeSaveEvent
{
	public function __construct(
		public Home $home,
	) {
		// throw new \Exception('Not implemented');
	}
}
