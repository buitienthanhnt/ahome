<?php

namespace Thanhnt\Ahomeglobal\Repository;

use Thanhnt\Ahomeglobal\Helper\ModelHelper;
use Thanhnt\Ahomeglobal\Models\Attr;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class RoomRepository
{
	public function __construct(
		protected Room $room,
		protected ModelHelper $modelHelper,
	) {
		// throw new \Exception('Not implemented');
	}
	public function createRoom(int $home_id, array $data)
	{
		// dd($data, RoomInterface::FORM_FIELDS);
		$newRoom = $this->room->factory()->create([
			...$this->modelHelper->massDataAttribute(RoomInterface::FILLED_FILEDS, $data),
			RoomInterface::HOME_ID => $home_id,
		]);
		if ($newRoom) {
			$this->saveRoomAttrs($newRoom, $data['attrs'] ?? []);
		}
	}

	/**
	 * @param Room $room
	 * @param array $data
	 * @return bool
	 */
	public function saveRoomAttrs($room, array $data)
	{
		$this->deleteRoomAttrs($room);

		$listAttr = [];
		/**
		 * format request home attribute
		 */
		foreach ($data as $key => $value) {
			$listAttr[] = [
				AttrInterface::SOURCE_ID => $room->id,
				AttrInterface::TYPE => 'room',
				AttrInterface::KEY => $key,
				AttrInterface::VALUE => $value,
			];
		}

		/**
		 * insert for multi record
		 * @return bool
		 */
		return $newAttr = Attr::insert($listAttr);
	}

	public function updateRoom() {}

	/**
	 * delete room item by id
	 * @param int $id
	 * @return void
	 */
	public function deleteRoom(int $id)
	{
		$room = $this->room->findOrFail($id);
		$room->delete();
		$this->deleteRoomAttrs($room);
	}

	/**
	 * @param Room $room
	 * @return void
	 */
	public function deleteRoomAttrs($room)
	{
		return $room->attr()->forceDelete();
	}
}
