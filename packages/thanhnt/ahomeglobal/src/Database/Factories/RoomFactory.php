<?php

namespace Thanhnt\Ahomeglobal\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;
use Thanhnt\Ahomeglobal\Models\Room;

class RoomFactory extends Factory implements RoomInterface
{
	/**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
	protected $model = Room::class;

	public function definition() {

		return [
			self::TITLE => $this->faker->name(),
			self::DESCRIPTION => $this->faker->paragraph(2),
			self::HOME_ID => 1,
			self::TYPE => RoomInterface::TYPE_VALUE[0],
		];
	}
}
