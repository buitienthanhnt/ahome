<?php
namespace App\Api\Data\Cart;

use App\Api\Data\Attribute;

class CartItem extends Attribute implements CartItemInterface
{
    function setQty(int $qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    function getQty()
    {
        return $this->getData(self::QTY);
    }

    function setValueId(int $id)
    {
        return $this->setData(self::VALUE_ID, $id);
    }

    function getValueId()
    {
        return $this->getData(self::VALUE_ID);
    }

    function setValueTitle(string $title)
    {
        return $this->setData(self::VALUE_TITLE, $title);
    }

    function getValueTitle()
    {
        return $this->getData(self::VALUE_TITLE);
    }

    function setValuePrice(float $price)
    {
        return $this->setData(self::VALUE_PRICE, $price);
    }

    function getValuePrice()
    {
        return $this->getData(self::VALUE_PRICE);
    }

    function setValuePriceFormat($price_format)
    {
        return $this->setData(self::VALUE_PRICE_FORMAT, $price_format);
    }

    function getValuePriceFormat()
    {
        return $this->getData(self::VALUE_PRICE_FORMAT);
    }

    function setValueImagePath(string $image_path)
    {
        return $this->setData(self::VALUE_IMAGE_PATH, $image_path);
    }

    function getValueImagePath()
    {
        return $this->getData(self::VALUE_IMAGE_PATH);
    }

    function setValueAlias(string $alias)
    {
        return $this->setData(self::VALUE_ALIAS);
    }

    function getValueAlias()
    {
        return $this->getData(self::VALUE_ALIAS);
    }
}
