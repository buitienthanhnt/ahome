<?php

namespace Thanhnt\Ahomeglobal\Listeners;

use Illuminate\Support\Facades\Log;
use Thanhnt\Ahomeglobal\Events\HomeSaveEvent;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

final class HomeSaveListener {

	/**
	 * well done for run event
	 */
	public function handle(HomeSaveEvent $event)
    {
		$home = $event->home;
		Log::warning('event listener: created new home item with id: '.$home->{HomeInterface::ID});
        // Logic to run when the event is fired
    }
}
