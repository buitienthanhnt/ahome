<?php
namespace App\Api\Data\Cart;

use App\Api\Data\AttributeInterface;

interface CartDataInterface extends AttributeInterface{
    const ITEMS = 'items';
    const TOTALS = 'totals';
    const COUNT = 'count';
    const CHECKOUT_URL = 'checkoutUrl';

    function setItems($items);

    function getItems();

    function setTotals($totals);

    function getTotals();

    function setCount(int $count);

    /**
     * @return int
     */
    function getCount();

    /**
     * @param string $checkout_url
     * @return $this
     */
    function setCheckoutUrl(string $checkout_url);

    /**
     * @return string
     */
    function getCheckoutUrl();
}