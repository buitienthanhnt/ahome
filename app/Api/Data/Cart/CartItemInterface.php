<?php
namespace App\Api\Data\Cart;

use App\Api\Data\AttributeInterface;

interface CartItemInterface extends AttributeInterface{
    const QTY = 'qty';
    const VALUE_PRICE = 'price';
    const VALUE_PRICE_FORMAT = 'priceFormat';
    const VALUE_ID = 'valueId';
    const VALUE_TITLE = 'title';
    const VALUE_IMAGE_PATH = 'imagePath';
    const VALUE_ALIAS = 'alias';

    function setQty(int $qty);

    function getQty();

    function getValuePrice();

    function setValuePrice(float $price);

    function setValuePriceFormat($price_format);

    function getValuePriceFormat();

    function setValueId(int $id);

    function getValueId();

    function setValueTitle(string $title);

    function getValueTitle();

    function setValueImagePath(string $image_path);

    function getValueImagePath();

    function setValueAlias(string $alias);

    function getValueAlias();
}