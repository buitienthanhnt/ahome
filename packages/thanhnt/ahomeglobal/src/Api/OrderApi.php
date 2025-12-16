<?php

namespace Thanhnt\Ahomeglobal\Api;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Thanhnt\Ahomeglobal\Helper\DateTimeHelper;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\OrderTime;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class OrderApi
{
	public function __construct(
		protected Order $order,
		protected OrderTime $orderTime,
		protected Room $room,
		protected DateTimeHelper $dateTimeHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * define function for create new order.
	 * @param int $home
	 * @param mixed|Request $data
	 * @return \Illuminate\Database\Eloquent\Collection<int, TModel>|TModel
	 * @throws Exception
	 */
	public function createNewOrder($data, $home = null,)
	{
		/**
		 * sort list input date from request 
		 */
		$dateValues = $this->dateTimeHelper->sortArrayDateString($data->array('values'));
		/**
		 * get active room by list date
		 * has 2 option: 1 dateRange, 2 date list
		 */
		$activeRoom = $this->getActiveRoomByDates($dateValues, $home)->get();
		if (!$activeRoom->count()) {
			throw new Exception('the input date not active');
		}

		/**
		 * create new order model
		 */
		return $this->order->factory()->create([
			OrderInterface::HOME_ID => $home,
			OrderInterface::ROOM_ID => $data->integer('room') ?: $activeRoom->random()->id,
			OrderInterface::DATE_FROM => $dateValues[0] ?? Carbon::now(),
			OrderInterface::DATE_TO => end($dateValues) ?? Carbon::now(),
			OrderInterface::SELECTED_TIME => $dateValues,
		]);
	}

	/**
	 * get list orders has order time in input range(support for use datetime range now only support array date)
	 * @param string $dateFrom ex: 2025-12-20
	 * @param string $dateTo   ex: 2025-12-27
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getDisableOrderByRange(string $dateFrom, string $dateTo)
	{
		return $this->order->where(function (Builder $query) use ($dateFrom, $dateTo) {
			$query->where(OrderInterface::DATE_FROM, '>=', $dateFrom)->where(OrderInterface::DATE_FROM, '<=', $dateTo);
		})
			->orWhere(function (Builder $query) use ($dateFrom, $dateTo) {
				$query->where(OrderInterface::DATE_TO, '>=', $dateFrom)->where(OrderInterface::DATE_TO, '<=', $dateTo);
			})->orWhere(function (Builder $query) use ($dateFrom, $dateTo) {
				$query->where(OrderInterface::DATE_FROM, '<=', $dateFrom)->where(OrderInterface::DATE_TO, '>=', $dateTo);
			})
			->get()
			->makeVisible([OrderInterface::ROOM_ID, OrderInterface::HOME_ID]);
	}

	/**
	 * get active room by input date range(support for use datetime range now only support array date)
	 * @param string $dateFrom ex: 2025-12-20
	 * @param string $dateTo   ex: 2025-12-27
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function activeRoomByRange(string $dateFrom, string $dateTo)
	{
		/**
		 * not need: booked_dates
		 */
		return $this->room->whereNotIn(
			RoomInterface::ID,
			$this->getDisableOrderByRange($dateFrom, $dateTo)
				->pluck([OrderInterface::ROOM_ID])->toArray()
		)->get()->makeHidden(['booked_dates']);
	}

	/**
	 * @param array[string] $listDate
	 * @param int $homeId
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getDisableRoomByDates($listDate = [], ?int $homeId = null)
	{
		$listBookedDate = $this->orderTime->whereIn(OrderTimeInterface::DATE, $listDate)->where(
			fn($builder) =>  $homeId ? $builder->where(OrderTimeInterface::HOME_ID, $homeId) : $builder
		)->get()
			->flatMap(function ($orderTime) {
				return $orderTime->{OrderTimeInterface::ROOM_IDS};
			})->unique();
		return $this->room->whereIn(RoomInterface::ID, $listBookedDate->toArray())->get();
	}

	/**
	 * @param array[string] $listDate
	 * @param int $homeId
	 * @return array[int]
	 */
	public function getDisableArrayRoomByDates($listDate = [], ?int $homeId = null): array
	{
		return $this->getDisableRoomByDates(listDate: $listDate, homeId: $homeId)->pluck([RoomInterface::ID])->toArray();
	}



	/**
	 * get all rooms has pass for list input dates
	 * @param array[string] $listDate
	 * @param int $homeId
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function getActiveRoomByDates(array $listDate = [], ?int $homeId = null)
	{
		return $this->room->where(
			fn($builder) =>  $homeId ? $builder->where(OrderTimeInterface::HOME_ID, $homeId) : $builder
		)->whereNotIn(RoomInterface::ID, $this->getDisableArrayRoomByDates($listDate));
	}

	/**
	 * 
	 */
	public function getActiveRoomPaginateByDates(array $listDate = [], ?int $homeId = null, int $limit = 12)
	{
		$rooms =  $this->room->where(
			fn($builder) =>  $homeId ? $builder->where(OrderTimeInterface::HOME_ID, $homeId) : $builder
		)->whereNotIn(RoomInterface::ID, $this->getDisableArrayRoomByDates($listDate))->paginate($limit, pageName: 'room_page');

		return $rooms->through(function ($room) {
			return $room->makeVisible(['booked_dates',])->makeVisible([RoomInterface::HOME_ID]);
		});
	}

	/**
	 * @param string[] $listDate [2025-12-12, 2025-12-13, 2025-12-14,...]
	 * @return Collection
	 */
	public function getActiveHomeIdByDate(array $listDate = [])
	{
		return $this->room->whereNotIn(RoomInterface::ID, $this->getDisableArrayRoomByDates($listDate))
			->select('home_id',)
			->distinct()
			->get()
			->makeHidden(['price', 'booked_dates'])
			->pluck('home_id');
	}

	/**
	 * 
	 */
	public function getActiveHomeByDate(array $listDate = [], $limit = 12)
	{
		return Home::whereIn(HomeInterface::ID, $this->getActiveHomeIdByDate($listDate))->paginate($limit);
	}

	/**
	 * @param array[string] $listDate
	 * @param int $homeId
	 * @return bool
	 */
	public function hasActiveRoomByDates(array $listDate = [], ?int $homeId = null)
	{
		return $this->room->where(
			fn($builder) =>  $homeId ? $builder->where(OrderTimeInterface::HOME_ID, $homeId) : $builder
		)->whereNotIn(RoomInterface::ID, $this->getDisableArrayRoomByDates(listDate: $listDate))->exists();
	}
}
