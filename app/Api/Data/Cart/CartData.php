<?php
namespace App\Api\Data\Cart;

use App\Api\Data\Attribute;

class CartData extends Attribute implements CartDataInterface
{
    function setItems($items)
    {
        return $this->setData(self::ITEMS, $items);
    }

    function getItems()
    {
        return $this->getData(self::ITEMS);
    }

    function setTotals($totals)
    {
        return $this->setData(self::TOTALS, $totals);
    }

    function getTotals()
    {
        return $this->getData(self::TOTALS);
    }

    function setCount(int $count)
    {
        return $this->setData(self::COUNT, $count);
    }

    function getCount()
    {
        return $this->getData(self::COUNT);
    }

    function setCheckoutUrl(string $checkout_url)
    {
        return $this->setData(self::CHECKOUT_URL, $checkout_url);
    }

    function getCheckoutUrl()
    {
        return $this->getData(self::CHECKOUT_URL);
    }
}
