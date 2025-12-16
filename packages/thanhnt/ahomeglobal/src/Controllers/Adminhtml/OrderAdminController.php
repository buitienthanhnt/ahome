<?php

namespace Thanhnt\Ahomeglobal\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\OrderTime;

final class OrderAdminController extends Controller
{
	public function __construct(
		protected Order $order,
		protected OrderTime $orderTime,
	)
	{
		// throw new \Exception('Not implemented');
	}

	public function deleteOrders(Request $request) {
		if ($id = $request->integer('id')) {
			$status = $this->order->find($id)?->forceDelete(); // delete
			return $status;
		}

	
		if ($request->input('id') === 'all') {
			$this->order->query()->forceDelete();
		}

		return 'this is message at end of process!';
	}

	public function deleteOrderTime(Request $request) {
			if ($id = $request->integer('id')) {
			$status = $this->orderTime->find($id)?->forceDelete(); // delete
			return $status;
		}

	
		if ($request->input('id') === 'all') {
			$this->orderTime->query()->forceDelete();
		}

		return 'this is message at end of process!';
	}
}
