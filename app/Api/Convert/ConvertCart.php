<?php
namespace App\Api\Convert;

use App\Api\Data\Cart\CartData;
use App\Api\Data\Cart\CartItem;
use App\Models\Paper;
use App\Models\PaperInterface;
use Thanhnt\Nan\Helper\TokenManager;

class ConvertCart
{
    protected $tokenManager;

    function __construct(
        TokenManager $tokenManager
    )
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param Paper $value
     */
    function convertCartItem($value, int $qty){
        $cartItem = new CartItem();
        $cartItem->setValueTitle($value->{PaperInterface::ATTR_TITLE});
        $cartItem->setValueAlias($value->{PaperInterface::ATTR_URL_ALIAS});
        $cartItem->setValueImagePath($value->getImagePath());
        $priceContent = $value->getPriceType();
        $cartItem->setValueId($value->id);
        $cartItem->setQty($qty);
        $cartItem->setValuePrice($priceContent->getPrice());
        $cartItem->setValuePriceFormat($priceContent->getPrice(true));
        return $cartItem;
    }

    function cartTotals(CartData $cartData)  {
        $value = 0;
        foreach ($cartData->getItems() as $item) {
            $value+=$item->getValuePrice()*$item->getQty();
        }
        return $value;
    }

    /**
     * @param CartData|null $cartData
     * @return CartData
     */
    function convertCartData($_cartData){
        $cartData = new CartData();
        if ($_cartData) {
            $cartItems = $_cartData->getItems();
            $cartData->setItems($cartItems);
            $cartData->setTotals($this->cartTotals($_cartData));
            $cartData->setCount(count($cartItems));
            $cartData->setCheckoutUrl(route('front_redirect', [
                'token' => $this->tokenManager->getTokenAuthor(),
                'url' => route('front_checkout')
            ]));
        }
        return $cartData;
    }

}
