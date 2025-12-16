<?php

namespace Thanhnt\Ahomeglobal\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

class HomeFactory extends Factory implements HomeInterface
{
	/**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
	protected $model = Home::class;

	public function definition() {
		return [
			self::NAME => $this->faker->name(),
			self::DESCRIPTION => $this->faker->paragraph(1),
			self::DISTRICT => $this->faker->paragraph(),
		];
	}
}
