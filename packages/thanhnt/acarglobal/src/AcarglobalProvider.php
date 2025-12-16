<?php

namespace Thanhnt\Acarglobal;

use Illuminate\Support\ServiceProvider;

final class AcarglobalProvider extends ServiceProvider
{
	/**
	 * 
	 */
	public function register(): void {}

	/**
	 * 
	 */
	public function boot(): void
	{
		/**
		 * load route file
		 */
		$this->loadRoutesFrom(__DIR__ . '/routes/front.php');
		/**
		 * load migration folder
		 * make migration: php artisan make:migration create_cars_table --path=packages/thanhnt/acarglobal/src/database/migrations
		 * make model: 	   php artisan acar:make-model Acar
		 */
		$this->loadMigrationsFrom(__DIR__ . '/database/migrations');

		/**
		 * khai báo các command
		 * dùng hàm: is_subclass_of; để kiểm tra các class định nghĩa trong đây: https://www.php.net/manual/en/function.is-subclass-of.php
		 */
		$this->commands([
			\Thanhnt\Acarglobal\Commands\MakePackageModelCommand::class,
		]);
	}
}
