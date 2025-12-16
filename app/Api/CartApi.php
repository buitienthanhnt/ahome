<?php

namespace App\Api;

use App\Api\Data\Cart\CartData;
use App\Api\Data\Cart\CartItem;
use App\Api\Convert\ConvertCart;
use App\Http\Exception\InputErrorException;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderAddressInterface;
use App\Models\OrderInterface;
use App\Models\OrderItem;
use App\Models\OrderItemInterface;
use App\Models\Paper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartApi
{
    const KEY = 'cart_session';

    protected $request;
    protected $order;
    protected $orderItem;
    protected $orderAddress;

    protected $convertCart;

    function __construct(
        Request $request,
        Order $order,
        OrderItem $orderItem,
        OrderAddress $orderAddress,
        ConvertCart $convertCart
    ) {
        $this->request = $request;
        $this->order = $order;
        $this->orderItem = $orderItem;
        $this->orderAddress = $orderAddress;
        $this->convertCart = $convertCart;
        $this->session_begin();
    }

    function addCart($paper, int $qty)
    {
        $current_cart = $this->getCart();
        if (!$current_cart) {
            $current_cart = $this->convertCart->convertCartData($current_cart);
        }
        /**
         * @var Paper $paperObj
         */
        $cartItem = $this->convertCart->convertCartItem($paper, $qty);
        $currentCartItems = $current_cart->getItems() ?: [];

        try {
            $_existItem = array_filter($currentCartItems, function ($item) use ($paper) {
                return $paper->id == $item->getValueId();
            });
            if (!$_existItem) {
                $currentCartItems[] = $cartItem;
            } else {
                foreach ($currentCartItems as &$value) {
                    if ($paper->id === $value->getValueId()) {
                        $value->setQty($value->getQty() + $qty);
                    }
                }
            }
            $current_cart->setItems($currentCartItems);
            $newCart = $this->convertCart->convertCartData($current_cart);
            $this->saveCart($newCart);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 500);
        }
        return $this->getCart();
    }

    function removeItem($item_id)
    {
        $cartData = $this->getCart();
        $cart_items = $cartData->getItems();
        $new_items = array_filter($cart_items, function (CartItem $item) use ($item_id) {
            return $item->getValueId() != $item_id;
        });
        $cartData->setItems(array_values($new_items));
        $this->saveCart($this->convertCart->convertCartData($cartData));
        return $this->getCart();
    }

    /**
     * @return CartData
     */
    function getCart()
    {
        $paper_cart = Session::get(self::KEY) ?: null;
        return $this->convertCart->convertCartData($paper_cart);
    }

    function submitOrder()
    {
        $cart = $this->getCart();
        $ship_store = $this->request->get(OrderAddressInterface::ATTR_SHIP_STORE);
        if (!$ship_store) {
            $ship_hc = $this->request->get(OrderAddressInterface::ATTR_SHIP_HC);
            $ship_nhc = $this->request->get(OrderAddressInterface::ATTR_SHIP_NHC);
            if (!$ship_hc && !$ship_nhc) {
                throw new InputErrorException("shipping type not define", 400);
            }
            $address_hc = $this->request->get(OrderAddressInterface::ATTR_ADDRESS_HC);
            $address_nhc = $this->request->get(OrderAddressInterface::ATTR_ADDRESS_NHC);
            if (!$address_hc && !$address_nhc) {
                throw new InputErrorException("shipping address not define", 400);
            }
        }
        try {
            $this->order->fill([
                OrderInterface::ATTR_STATUS => OrderInterface::STATUS_PENDING,
                OrderInterface::ATTR_EMAIL => $this->request->get(OrderInterface::ATTR_EMAIL),
                OrderInterface::ATTR_NAME => $this->request->get(OrderInterface::ATTR_NAME),
                OrderInterface::ATTR_PHONE => $this->request->get(OrderInterface::ATTR_PHONE),
                OrderInterface::ATTR_THANH_TOAN => $this->request->get(OrderInterface::ATTR_THANH_TOAN),
                OrderInterface::ATTR_OMX => $this->request->get(OrderInterface::ATTR_OMX, '0000000'),
                OrderInterface::ATTR_TOTAL => $cart->getTotals()
            ])->save();
            if ($order_id = $this->order->id) {
                // save order address
                $this->orderAddress->fill([
                    OrderAddressInterface::ATTR_JOIN_ID => $order_id,
                    OrderAddressInterface::ATTR_SHIP_HC => boolval($this->request->get(OrderAddressInterface::ATTR_SHIP_HC)),
                    OrderAddressInterface::ATTR_ADDRESS_HC => $this->request->get(OrderAddressInterface::ATTR_ADDRESS_HC),
                    OrderAddressInterface::ATTR_SHIP_NHC => boolval($this->request->get(OrderAddressInterface::ATTR_SHIP_NHC)),
                    OrderAddressInterface::ATTR_ADDRESS_NHC => $this->request->get(OrderAddressInterface::ATTR_ADDRESS_NHC),
                    OrderAddressInterface::ATTR_SHIP_STORE => boolval($this->request->get(OrderAddressInterface::ATTR_SHIP_STORE))
                ])->save();
                // save for order items
                foreach ($cart->getItems() as $item) {
                    /**
                     * @var CartItem $item
                     */
                    $order_item = [
                        OrderItemInterface::ATTR_PAPER_ID => $item->getValueId(),
                        OrderItemInterface::ATTR_TITLE => $item->getValueTitle(),
                        OrderItemInterface::ATTR_QTY => $item->getQty(),
                        OrderItemInterface::ATTR_ORDER_ID => $order_id,
                        OrderItemInterface::ATTR_PRICE => $item->getValuePrice(),
                        OrderItemInterface::ATTR_URL => $item->getValueAlias(),
                        OrderItemInterface::ATTR_IMAGE_PATH => $item->getValueImagePath()
                    ];
                    $this->orderItem->create($order_item);
                }
            }
            $this->clearCart();
            return [
                "status" => true,
                "message" => 'created new order',
                "order_id" => $order_id
            ];
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    function xoaItem($id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->saveCart($cart);
    }

    function clearCart()
    {
        Session::forget(self::KEY);
        Session::save();
        return $this->getCart();
    }

    public function session_begin(): void
    {
        if (!Session::isStarted()) {
            Session::start();
        }
    }

    function saveCart($cart)
    {
        Session::put(self::KEY, $cart);
        Session::save();
    }
}
