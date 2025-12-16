<?php

namespace App\Models;

interface OrderAddressInterface
{
    const TABLE_NAME = 'order_addresses';

    const ATTR_JOIN_ID = OrderInterface::PRIMARY_ALIAS;
    const ATTR_SHIP_HC = 'ship_hc';
    const ATTR_SHIP_NHC = 'ship_nhc';
    const ATTR_ADDRESS_HC = 'address_hc';
    const ATTR_ADDRESS_NHC = 'address_nhc';
    const ATTR_SHIP_STORE = 'ship_store';

}
