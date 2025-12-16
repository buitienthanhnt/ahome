<?php

namespace Thanhnt\Ahomeglobal;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Thanhnt\Ahomeglobal\Providers\PackageEventServiceProvider;

final class AhomeglobalProvider extends ServiceProvider
{
	/**
	 * 
	 */
	public function register(): void
	{
		/**
		 * merge package define config to global.
		 */
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'ahomeglobal');

		/**
		 * load for event service provider.
		 */
		$this->app->register(PackageEventServiceProvider::class);
	}

	/**
	 * 
	 */
	public function boot(): void
	{
		// Load views, routes, migrations, publish assets, etc.
		$this->loadViewsFrom(__DIR__ . '/resources/views', 'ahomeglobal');
		/**
		 * load router file register
		 * need define web middleware for router unless the request missing session data. 
		 */
		Route::middleware([
			\Illuminate\Session\Middleware\StartSession::class,
			\App\Http\Middleware\HandleInertiaRequests::class,
			\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
		])->group(function () {
			$this->loadRoutesFrom(__DIR__ . '/routes/adminhtml.php');
			$this->loadRoutesFrom(__DIR__ . '/routes/front.php');
		});

		/**
		 * load migration define 
		 * make migration: php artisan make:migration create_homes_table --path=packages/thanhnt/ahomeglobal/src/Database/Migrations
		 * rollback: php artisan migrate:rollback --step=1
		 */
		$this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
		/**
		 * load factory for package
		 */
		$this->loadFactoriesFrom(__DIR__ . '/Database/Factories');

		/**
		 * coppy config file from the package to global config
		 * php artisan vendor:publish --provider="Thanhnt\Ahomeglobal\AhomeglobalProvider"
		 */
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('ahomeglobal.php'),
		], 'ahomeglobal-config');

		/**
		 * publish inertiaJs component to js/Pages views and active running with controllers Inertial::render()
		 * php artisan vendor:publish --tag=ahomeglobal-inertiajs
		 */
		$this->publishes([
			__DIR__ . '/resources/js' => resource_path('js/Pages'),
		], 'ahomeglobal-inertiajs');

		/**
		 * publish Database seeders to seeders global
		 * php artisan vendor:publish --tag=ahomeglobal-seeders
		 * Hiện tại không cần xử lý đoạn pushlish này vì có thể gọi trực tiếp class Seeder.
		 * php artisan db:seed --class=Thanhnt\\Ahomeglobal\\Database\\Seeders\\OrderSeeder 
		 */
		// $this->publishes([
		//     __DIR__.'/Database/Seeders' => database_path('seeders'),
		// ], 'ahomeglobal-seeders');
	}
}
