<?php

namespace Thanhnt\Ahomeglobal\Api;

use Illuminate\Support\Facades\DB;
use Thanhnt\Ahomeglobal\Models\Attr;
use Thanhnt\Ahomeglobal\Models\OrderTime;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class RoomApi
{
	public function __construct(
		protected Room $roomModel,
		protected OrderTime $orderTime,
		protected OrderApi $orderApi,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * @param int $roomId
	 * @return \Thanhnt\Ahomeglobal\Models\Room|null
	 */
	public function getRoomDetail(int $roomId)
	{
		return $this->roomModel->with('orders')->find($roomId);
	}

	/**
	 * @param int $roomId
	 * @return \Thanhnt\Ahomeglobal\Models\Room|null
	 */
	public function getRoomDetailNoOrders(int $roomId)
	{
		return $this->roomModel->setVisible(['booked_dates'])->find($roomId);
	}

	public function roomPaginate(int $limit = 12)
	{
		return $this->roomModel->paginate($limit);
	}

	/**
	 * @param array $selected
	 * @return array
	 */
	public function allFilters($selected = [])
	{
		$roomFilterFields = RoomInterface::CUSTOM_ATTRS;
		$homeFilterFields = RoomInterface::CUSTOM_ATTRS;
		/**
		 * get key and group district value on all_values
		 */
		$attrs = Attr::where(AttrInterface::TYPE, 'room')->select('key', DB::raw("GROUP_CONCAT(DISTINCT value) as all_values"))->groupBy('key')->get();
		foreach ($roomFilterFields as &$value) {
			/**
			 * format for price range(manual)
			 */
			if ($value['key'] === 'price') {
				$listValue = explode(',', $attrs->filter(function ($val) use ($value) {
					return $val->key === $value['key'];
				})->first()->all_values);
				sort($listValue);
				$min = $listValue[0];
				$max = end($listValue);
				$range = ($max - $min) / 4;

				/**
				 * explode for 4 sub range
				 */
				$options = [];
				for ($i = 0; $i < 4; $i++) {
					$_value = floor($min + $range * $i) . '-' . floor($min + $range * ($i + 1));
					$label = floor($min + $range * $i) . '$ -' . floor($min + $range * ($i + 1)) . '$';

					$options[] = [
						'value' => $_value,
						'label' => $label,
						'selected' => ($selected[$value['key']] ?? null) === $_value,
					];
				}
				$value['data'] = $options;
			} else {
				/**
				 * format auto filter room custom attribute
				 */
				$value['data'] = array_map(function ($val) use ($value, $selected) {
					return [
						'value' => $val,
						'label' => $val,
						'selected' => $val === ($selected[$value['key']] ?? null),
					];
				}, explode(',', $attrs->filter(function ($val) use ($value) {
					return $val->key === $value['key'];
				})->first()->all_values));
			}
		}
		return array_values($roomFilterFields);
	}

	/**
	 * @param string[] $listDate
	 * @param int $limit
	 */
	public function getActiveRoomByDate($listDate, $limit)
	{
		return $this->orderTime->getActiveRoomByDate($listDate);
	}

	public function paginateRoomWithFilter($filterParams, $limit)
	{
		/**
		 * no filter params
		 */
		if (!$filterParams) {
			return Room::paginate($limit, pageName: 'room_page')->through(function ($room) {
				return $room->makeHidden(['booked_dates',])->makeVisible([RoomInterface::HOME_ID]);
			});
		}

		/**
		 * date filter
		 */
		if ($listDate = $filterParams['dates'] ?? null) {
			$instance = $this->orderApi->getActiveRoomByDates(listDate: $listDate);
		} else {
			$instance = Room::query();
		}

		/**
		 * filter by custom attribute
		 */
		$listFilters = array_intersect_key($filterParams, RoomInterface::CUSTOM_ATTRS);
		foreach ($listFilters as $key => $value) {
			switch ($key) {
				case 'price':
					$instance->whereHas('attr', function ($query) use ($key, $value) {
						$query->where(AttrInterface::KEY, $key)->whereBetween(AttrInterface::VALUE, explode('-', $value));
					});
					break;
				default:
					$instance->whereHas('attr', function ($query) use ($key, $value) {
						$query->where(AttrInterface::KEY, $key)->where(AttrInterface::VALUE, $value);
					});
					break;
			}
		}
		/**
		 * dùng: [through] để hiển thị: HOME_ID khi dùng paginate phân trang 
		 * nếu không nó sẽ chỉ trả về danh sách kết quả mà không có các thuộc tính phân trang
		 */
		return $instance->paginate($limit, pageName: 'room_page')->through(function ($room) {
			return $room->makeHidden(['booked_dates',])->makeVisible([RoomInterface::HOME_ID]);
		});
	}
}
