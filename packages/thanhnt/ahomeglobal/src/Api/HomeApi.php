<?php

namespace Thanhnt\Ahomeglobal\Api;

use Thanhnt\Ahomeglobal\Models\Attr;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

use function PHPSTORM_META\map;

final class HomeApi
{
	public function __construct(
		protected Home $home,
		protected OrderApi $orderApi,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * get home detail for Inertia
	 * @param int $homeId
	 * @return \Thanhnt\Ahomeglobal\Models\Home
	 */
	public function getHomeDetail(int $homeId)
	{
		$home =  $this->home->with('rooms')->with('orderTimes')->with('attr')->find($homeId);
		/**
		 * set hidden for: booked_dates attribute(not need in homeDetail)
		 */
		$home->rooms->setHidden(['booked_dates', ...RoomInterface::HIDDEN_FIELDS]);
		return $home;
	}

	public function homePaginate(int $limit = 12)
	{
		return $this->home->paginate($limit);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	protected function getHomeByDistrict(string $district)
	{
		// $homeList =  Home::whereRaw('district LIKE ? COLLATE utf8mb4_unicode_ci', ['%'.$district.'%']); // mysql
		$homeList =  Home::whereLike(HomeInterface::DISTRICT, "%$district%");
		return $homeList;
	}

	/**
	 * get list home id filter by Room custom attribute.
	 */
	protected function getHomeIdfilterByCustomAttr($filterParams): array
	{
		$listFilters = array_intersect_key($filterParams, RoomInterface::CUSTOM_ATTRS);
		$instance = Room::query()->with('home');

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
		return $instance->get()->makeHidden(['booked_dates', 'price'])->pluck('home.id')->unique()->toArray();
	}

	protected function filterHomeByRate() {}

	/**
	 * get list home by filter attribute room
	 */
	public function paginateHomeWithFilter($filterParams = [], $limit = 6)
	{
		if ($filterParams) {
			/**
			 * filter home by name
			 */
			if ($district = $filterParams['district'] ?? null) {
				$homeIds = $this->getHomeByDistrict($district)->select('id')->get()->pluck(['id'])->toArray();
			}

			/**
			 * filter home by date.
			 */
			if ($seletedDates = $filterParams['dates'] ?? null) {
				$homeIds = isset($homeIds) ? array_intersect($this->orderApi->getActiveHomeIdByDate($seletedDates)->toArray(), $homeIds) : $this->orderApi->getActiveHomeIdByDate($seletedDates)->toArray();
			}

			/**
			 * filter by custom attribute of room
			 */
			$homeIds = isset($homeIds) ? array_intersect($this->getHomeIdfilterByCustomAttr($filterParams), $homeIds) : $this->getHomeIdfilterByCustomAttr($filterParams);

			return Home::whereIn(HomeInterface::ID, $homeIds ?? [])->withWhereHas('rooms')->paginate($limit);
		}
		/**
		 * return default home list
		 */
		return Home::withWhereHas('rooms')->paginate($limit);
	}
}
