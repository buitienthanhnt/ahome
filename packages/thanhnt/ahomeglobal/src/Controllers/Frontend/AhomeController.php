<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\HomeApi;
use Thanhnt\Ahomeglobal\Api\RoomApi;
use Thanhnt\Ahomeglobal\Helper\DateTimeHelper;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\OrderTime;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;
use Thanhnt\Ahomeglobal\Api\OrderApi;

final class AhomeController extends Controller
{
	function __construct(
		protected HomeApi $homeApi,
		protected RoomApi $roomApi,
		protected OrderApi $orderApi,
		protected DateTimeHelper $dateTimeHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * show home of ahomeglobal frontend package
	 * @return \Inertia\Response
	 */
	public function home()
	{
		return Inertia::render('Ahomeglobal/Screens/Ahome',);
	}

	/**
	 * create new Home by factory
	 * @return \Illuminate\Database\Eloquent\Collection<int, TModel>|TModel
	 */
	public function createHome()
	{
		return Home::factory()->create();
	}

	/**
	 * show all of home are woking
	 * @return \Inertia\Response
	 */
	public function listHome(Request $request)
	{
		return Inertia::render('Ahomeglobal/Screens/HomeList', [
			"homes" => $this->homeApi->paginateHomeWithFilter($request->get('filters'), limit: 3),
			'rooms' => $this->roomApi->paginateRoomWithFilter($request->get('filters'), 6),
			'allFilters' => $this->roomApi->allFilters(selected: $request->get('filters')),
			"filters" => $request->get('filters'),
		]);
	}

	/**
	 * js library for calendar time
	 * https://www.npmjs.com/package/react-calendar
	 * https://www.npmjs.com/package/react-calendar-timeline
	 * @param int $home
	 * @return \Inertia\Response
	 */
	public function homeDetail(\Illuminate\Http\Request $request, $home)
	{
		// code...
		/**
		 * please get all order of the room then pass to Js page for disable the day selected.
		 */
		if ($request->isMethod('POST')) {
			$newOrder = $this->orderApi->createNewOrder($request, $home,);
			Inertia::share('messages',  'created for order with id: ' . $newOrder->{OrderInterface::ID});
		}

		return Inertia::render(
			'Ahomeglobal/Screens/HomeDetail',
			[
				'homeDetail' => $this->homeApi->getHomeDetail($home),
				'roomSelected' => Inertia::defer(function () use ($request) {
					if (!$request->integer('room')) {
						return null;
					}
					$room = $this->roomApi->getRoomDetailNoOrders($request->integer('room'));
					return $room;
				}),
				'selectedDates' => $request->get('selectedDates', []), // pass selected dates from query string(can be from homelist filter or home detail selected dates)
			],
		);
		return $home;
	}

	/**
	 * get all room and link home to this
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function listRoom()
	{
		/**
		 * visible attribute" home_id, created_at (auto hidden in model).
		 */
		$rooms = Room::with('home')->get()->makeVisible([RoomInterface::HOME_ID, 'created_at']);
		return $rooms;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function listOrder()
	{
		$orders = Order::with(['room', 'home'])->get();
		return $orders;
	}

	public function activeRoom(Request $request)
	{
		$listDate = ['2025-12-13', '2025-12-14', '2025-12-15'];
		// $noOrder = OrderTime::whereNotIn(OrderTimeInterface::DATE, $listDate)->get();
		$rangeDate = ['2025-12-26', '2025-12-31'];
		// $conflicRooms = Order::where('date_from' , '>=', $rangeDate[0])->where('date_from', '<=', $rangeDate[1])->orWhere(function (Builder $query) use($rangeDate){
		// 	$query->where('date_to', '>=' ,$rangeDate[0])->where('date_to', '=<', $rangeDate[1]);
		// })->orWhere(function (Builder $query)use($rangeDate) {
		// 	$query->where('date_from', '<=',$rangeDate[0])->where('date_to', '>=', $rangeDate[1]);
		// })->get()->makeVisible([OrderInterface::ROOM_ID, OrderInterface::HOME_ID])->pluck(['room_id'])->toArray();

		$listActives = $this->orderApi->activeRoomByRange($rangeDate[0], $rangeDate[1]); // Room::whereNotIn('id', $conflicRooms)->get()->makeHidden(['booked_dates'])->toArray();
		dd($listActives->toArray());
		/**
		 * tim cac phong da dat trong khoang thoi gian nay:
		 */
		$listBookedRooms = OrderTime::whereIn(OrderTimeInterface::DATE, $listDate)->get()->flatMap(function ($room) {
			return $room->room_ids;
		})->unique();

		$listActiveRoom = Room::whereNotIn('id', $listBookedRooms)->with('home')->get();
		return $listActiveRoom;
	}
}
