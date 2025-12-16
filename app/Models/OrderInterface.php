<?php
namespace App\Models;


interface OrderInterface
{
    const TABLE_NAME = 'orders';

    const ATTR_STATUS = 'status';
    const ATTR_NAME = 'name';
    const ATTR_EMAIL = 'email';
    const ATTR_PHONE = 'phone';
    const ATTR_TOTAL = 'total';
    const ATTR_THANH_TOAN = 'thanh_toan';
    const ATTR_OMX = 'omx';

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';

    const PRIMARY_ALIAS = 'order_id';
}
