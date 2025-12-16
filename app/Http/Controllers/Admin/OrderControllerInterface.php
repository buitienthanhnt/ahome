<?php
namespace App\Http\Controllers\Admin;

interface OrderControllerInterface{
    const CONTROLLER_NAME = 'Admin\OrderController';
    const PREFIX = 'orders';

    const LIST_ORDER = 'listOrder';
    const DETAIL_ORDER = 'detailOrder';

    public function listOrder();

    /**
     * @param int $order_id
     * @return mixed
     */
    public function detailOrder(int $order_id);
}
