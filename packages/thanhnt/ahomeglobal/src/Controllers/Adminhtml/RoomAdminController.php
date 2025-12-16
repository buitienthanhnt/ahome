<?php

namespace Thanhnt\Ahomeglobal\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Thanhnt\Ahomeglobal\Api\RoomApi;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;
use Thanhnt\Ahomeglobal\Repository\RoomRepository;

final class RoomAdminController extends Controller
{
	public function __construct(
		protected RoomRepository $roomRepository,
		protected RoomApi $roomApi,
	) {
		// throw new \Exception('Not implemented');
	}

	public function list() {
		$roomPaginate = $this->roomApi->roomPaginate(8);
		$actions = [
			[
				'type' => 'edit',
				'url' => RoomInterface::ROUTE_PREFIX . '/room-edit/',
				'label' => '',
				'icon' => 'edit',
			],
			[
				'type' => 'delete',
				'url' => RoomInterface::ROUTE_PREFIX . '/room-delete/',
				'label' => '',
				'icon' => 'delete',
			],
		];

		return view('adminhtml.ahome.pages.ahomeglobal.rooms.index', [
			'attributes' => [
				RoomInterface::ID,
				RoomInterface::TITLE,
				RoomInterface::DESCRIPTION,
			],
			'lists' => $roomPaginate,
			'actions' => $actions,
			'title' => 'list of room'
		]);
	}

	public function create($home_id)
	{
		$listAttributes = RoomInterface::FORM_FIELDS;
		return view('adminhtml.ahome.pages.ahomeglobal.rooms.create', [
			'listAttributes' => $listAttributes,
			'action' => '/adminhtml/ahome/room-register/' . $home_id,
			'optionAttribute' => array_map(function ($field) {
				return [
					...$field,
					'key' => "attrs[" . $field['key'] . "]",
				];
			}, RoomInterface::CUSTOM_ATTRS)
		]);
	}

	public function register($home_id, Request $request)
	{
		// dd($home_id, $request->all());
		$this->roomRepository->createRoom($home_id, $request->all());
		return redirect()->back()->with('message', 'created new room!');
	}

	public function roomDelete(int $id, Request $request){
		$this->roomRepository->deleteRoom($id);
		return response()->json([
			"code" => 200,
			"message" => "deleted for room with id: ". $id,
		]);
	}
}
