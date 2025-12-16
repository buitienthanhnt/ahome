<?php
namespace App\Models;

interface OrderItemInterface{
    const TABLE_NAME = 'order_items';

    const ATTR_ORDER_ID = OrderInterface::PRIMARY_ALIAS;
    const ATTR_PAPER_ID = 'paper_id';
    const ATTR_TITLE = 'title';
    const ATTR_PRICE = 'price';
    const ATTR_QTY = 'qty';
    const ATTR_URL = 'url';
    const ATTR_IMAGE_PATH = 'image_path';
}
