<?php

namespace Thanhnt\Amuaglobal;

use Illuminate\Support\ServiceProvider;

class AmuaglobalProvider extends ServiceProvider
{
	public function register()
	{
		// Register bindings, singletons, etc.
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'amuaglobal');
	}

	public function boot()
	{
		// Load views, routes, migrations, publish assets, etc.
		$this->loadViewsFrom(__DIR__ . '/resources/views', 'amuaglobal');
		$this->loadRoutesFrom(__DIR__ . '/routes/front.php');
		$this->loadRoutesFrom(__DIR__ . '/routes/adminhtml.php');
		$this->loadMigrationsFrom(__DIR__.'/database/migrations');
		$this->loadFactoriesFrom(__DIR__.'/database/factories');
		/**
		 * khai báo các command
		 * dùng hàm: is_subclass_of; để kiểm tra các class định nghĩa trong đây: https://www.php.net/manual/en/function.is-subclass-of.php
		 */
		$this->commands([
			\Thanhnt\Amuaglobal\Commands\DemoCommand::class, 
		]);

		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('amuaglobal.php'),
		], 'amuaglobal-config');
	}
}
