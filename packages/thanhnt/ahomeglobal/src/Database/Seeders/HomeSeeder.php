<?php

namespace Thanhnt\Ahomeglobal\Database\Seeders;

use Illuminate\Database\Seeder;
use Thanhnt\Ahomeglobal\Models\Home;

final class HomeSeeder extends Seeder
{
	/**
	 * run: php artisan db:seed --class=Thanhnt\\Ahomeglobal\\Database\\Seeders\\HomeSeeder
	 */
	public function run(): void
	{
		/**
		 * call to createHome protected function
		 */
		$this->createHome();
	}

	/**
	 * create new home row by factory
	 */
	protected function createHome()
	{
		Home::factory()->create();
	}
}
