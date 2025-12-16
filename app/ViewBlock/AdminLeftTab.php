<?php

namespace App\ViewBlock;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminLeftTab implements Htmlable
{
	protected $template = "adminhtml.templates.share.leftTab";
	protected $router;
	protected $request;
	const ROUTE_LEFT = 'admin_get_routes';
	const EXCLUDE_ROUTES = [
		'edit', 'detail', 'file/manager/upload', 'file/manager/move', 'file/manager/domove',
		'file/manager/crop', 'file/manager/cropimage', 'file/manager/cropnewimage',
		'file/manager/rename', 'file/manager/resize', 'file/manager/doresize',
		'file/manager/delete'
	];

	public function __construct(
		Router $router,
		Request $request
	) {
		$this->router = $router;
		$this->request = $request;
	}

	protected function checkExclude(string $value): bool
	{
		if (self::EXCLUDE_ROUTES) {
			foreach (self::EXCLUDE_ROUTES as $val) {
				if (strpos($value, $val) !== false) {
					return true;
				}
			}
		}
		return false;
	}

	protected function format_icon($key = ''): string
	{
		// https://pictogrammers.github.io/@mdi/font/2.0.46/
		switch ($key) {
			case '/adminhtml':
				return 'mdi-home-automation';
			case 'adminhtml/category':
				return 'mdi-card-text-outline';
			case 'adminhtml/paper':
				return 'mdi-chart-line';
			case 'adminhtml/permission':
				return 'mdi-table';
			case 'adminhtml/writer':
				return 'mdi-layers-outline';
			case 'adminhtml/rule':
				return 'mdi-account-circle-outline';
			case 'adminhtml/user':
				return 'mdi-file-document';
			case 'adminhtml/file/manager':
				return 'mdi-attachment';
			case 'adminhtml/config':
				return 'mdi-attachment';
			case 'adminhtml/firebase':
				return 'mdi-atom';
				// default:
				// 	return 'mdi-sitemap';
		}
		return 'mdi-floor-plan';
	}

	protected function actionByController($actions = []): array
	{
		if ($actions) {
			$prefixGroups = [];
			foreach ($actions as $k => $action) {
				if ($this->checkExclude($k) || strpos($k, '{') !== false) { // adminhtml/permission/edit/{permission_id}
					continue;
				}
				$prefixGroups[$action["prefix"]][] = [
					"Name"     => explode("@", $action["controller"], 2)[1],
					"Number"   => str_replace(["\\", "/", "@"], ["-", "__", "_"], $action["controller"]),
					"Children" => [],
					"as" => $action["as"],
				];
			}

			$rulesTree = [];
			foreach ($prefixGroups as $key => $value) {
				$rulesTree[] = [
					"Name"      => str_replace(["/", "adminhtml"], [" ", "admin"], $key),
					"Number"    => $key,
					"Children"  => $value,
					"icon" => $this->format_icon($key)
				];
			}
			return [
				"Name" => "root admin",
				"Number" => "rootAdmin",
				"Children" => $rulesTree,
			];
		}
		return [];
	}

	protected function routeListOfGet()
	{
		if (Cache::has(self::ROUTE_LEFT)) {
			return Cache::get(self::ROUTE_LEFT);
		}
		$allOfRoute = $this->router->getRoutes();
		$actions = collect($allOfRoute->get('GET'))->map(function ($item) {
			try {
				$action =  $item->getAction();
				if (strpos($action["prefix"], "adminhtml") !== false) {
					return $action;
				}
			} catch (\Throwable $th) {
				//throw $th;
			}
		})->filter();
		$values = $this->actionByController($actions)['Children'];
		Cache::put(self::ROUTE_LEFT, $values);
		return ($values);
	}

	function toHtml(): string
	{
		$formatRoutes = $this->routeListOfGet();
		return view($this->template, ['formatRoutes' => $formatRoutes])->render();
	}
}
