<?php

namespace Thanhnt\Ahomeglobal\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Thanhnt\Ahomeglobal\Api\HomeApi;
use Thanhnt\Ahomeglobal\Helper\ModelHelper;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;
use Thanhnt\Ahomeglobal\Repository\HomeRepository;

final class HomeAdminController extends Controller
{
	public function __construct(
		protected HomeApi $homeApi,
		protected HomeRepository $homeRepository,
		protected ModelHelper $modelHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	public function delete($id, Request $request)
	{
		$this->homeRepository->deleteHome((int) $id);
		return response()->json([
			'code' => 200,
			'message' => "deleted for home with id: " . $id,
		]);
	}

	public function homes()
	{
		$homePaginate = $this->homeApi->homePaginate();
		$actions = [
			[
				'type' => 'edit',
				'url' => HomeInterface::ROUTE_PREFIX . '/home-edit/',
				'label' => '',
				'icon' => 'edit',
			],
			[
				'type' => 'delete',
				'url' => HomeInterface::ROUTE_PREFIX . '/home-delete/',
				'label' => '',
				'icon' => 'delete',
			],
		];

		return view('adminhtml.pages.ahomeglobal.homes.list', [
			'attributes' => [
				HomeInterface::ID,
				HomeInterface::NAME,
				HomeInterface::DESCRIPTION,
			],
			'lists' => $homePaginate,
			'actions' => $actions,
		]);
	}

	/**
	 * @return \Illuminate\Contracts\View\View
	 */
	public function create()
	{
		$listAttributes = HomeInterface::FORM_FIELDS;
		return view('adminhtml.pages.ahomeglobal.homes.create', [
			'listAttributes' => $listAttributes,
			'optionAttribute' => array_map(function ($field) {
				return [
					...$field,
					'key' => "attrs[" . $field['key'] . "]",
				];
			}, HomeInterface::CUSTOM_ATTRS)
		]);
	}

	public function register(Request $request)
	{
		$this->homeRepository->createHome($request->all());
		return redirect()->back()->with('message', 'created new homes');
	}

	public function edit($id, Request $request)
	{
		$home = $this->homeApi->getHomeDetail($id);
		$listAttributes = $this->modelHelper->formAttribute(HomeInterface::FORM_FIELDS, $home->toArray());
		$optionAttribute = array_map(function ($field) {
			return [
				...HomeInterface::CUSTOM_ATTRS[$field['key']],
				'key' => "attrs[" . $field['key'] . "]",
				'value' => $field['value'],
			];
		}, $home->attr->toArray());

		return view('adminhtml.pages.ahomeglobal.homes.edit', [
			'listAttributes' => $listAttributes,
			'optionAttribute' => $optionAttribute,
			'action' => '/' . HomeInterface::ROUTE_PREFIX . '/home-update/' . $home->id,
			'home' => $home,
		]);
	}

	public function update($id, Request $request)
	{
		$this->homeRepository->updateHome($id, $request->all());
		return redirect()->back()->with('message', 'updated for the home');
	}
}
