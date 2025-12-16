<?php

namespace Thanhnt\Ahomeglobal\Repository;

use Exception;
use Thanhnt\Ahomeglobal\Events\HomeSaveEvent;
use Thanhnt\Ahomeglobal\Helper\ModelHelper;
use Thanhnt\Ahomeglobal\Models\Attr;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

final class HomeRepository
{
	public function __construct(
		protected Home $home,
		protected Attr $attr,
		protected ModelHelper $modelHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * create new home model
	 * @return Home
	 */
	public function createHome(array $data)
	{
		$newHome = $this->home->factory()->create($this->modelHelper->massDataAttribute(HomeInterface::FILLED_FILEDS, $data));
		if ($newHome) {
			\Illuminate\Support\Facades\Event::dispatch(new HomeSaveEvent($newHome));
			$this->saveHomeAttr($newHome, $data['attrs'] ?? null);
		}
		return $newHome;
	}

	/**
	 * insert multil record home attribute
	 * @param Home $home
	 * @param array $data
	 * @return bool
	 */
	protected function saveHomeAttr(Home $home, array|null $data)
	{
		$this->deleteHomeAttrs($home);

		$listAttr = [];
		/**
		 * format request home attribute
		 */
		foreach ($data as $key => $value) {
			$listAttr[] = [
				AttrInterface::SOURCE_ID => $home->id,
				AttrInterface::TYPE => 'home',
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

	/**
	 * 
	 */
	public function updateHome($homeId, $data)
	{
		/**
		 * @var Home|null $home
		 */
		$home = $this->home->find($homeId);
		if (!$home) {
			return;
		}
		$home->fill($data);
		$home->save();
		$this->saveHomeAttr($home, $data['attrs'] ?? []);
	}

	/**
	 * @param Home $home
	 * @return void
	 */
	public function deleteHomeAttrs($home)
	{
		$home->attr()->forceDelete();
	}

	/**
	 * @param Home $home
	 * @return void
	 */
	public function deleteRooms($home)
	{
		$home->room()->delete();
	}

	public function deleteHome(int $homeId)
	{
		$home = $this->home->find($homeId);
		if (!$home) {
			throw new Exception('the require id not exist');
		}
		$home->delete();
		$this->deleteHomeAttrs($home);
		$this->deleteRooms($home);
	}
}
