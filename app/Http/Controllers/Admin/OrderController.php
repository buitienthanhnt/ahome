<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller implements OrderControllerInterface
{
    /**
     * @var Order $order
     */
    protected $order;

    function __construct(
        Order $order
    )
    {
        $this->order = $order;
    }

    function listOrder() : View {
        $orders = $this->order->paginate(8);
        return view("adminhtml.templates.orders.list", ["orders" => $orders]);
    }

    /**
     * @param int $order_id
     * @return View
     */
    function detailOrder(int $order_id) {
        /**
         * @var Order $order
         */
        $order = $this->order->find($order_id);
        return view("adminhtml.templates.orders.detail", ['order' => $order]);
    }
}
