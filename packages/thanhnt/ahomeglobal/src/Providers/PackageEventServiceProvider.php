<?php

namespace Thanhnt\Ahomeglobal\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Thanhnt\Ahomeglobal\Events\HomeSaveEvent;
use Thanhnt\Ahomeglobal\Listeners\HomeSaveListener;

final class PackageEventServiceProvider extends EventServiceProvider
{
	/**
	 * The event listener mappings for the package.
	 *
	 * @var array
	 */
	protected $listen = [
		HomeSaveEvent::class => [
			HomeSaveListener::class,
		],
	];

	/**
	 * Register any package authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();
	}
}
