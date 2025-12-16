<?php
namespace App\Http\Controllers\Api;

interface CartApiControllerInterface{
    const CONTROLLER_NAME = 'Api\CartApiController';

    const ADD_TO_CART = 'addToCart';
    const GET_CART = 'getCart';
    const CLEAR_CART = 'clearCart';
    const REMOVE_CART_ITEM = 'removeItem';
    const SUBMIT_ORDER = 'submitOrder';

    function addToCart();

    function getCart();

    function clearCart();

    function removeItem(int $item_id);

    function submitOrder();
}
