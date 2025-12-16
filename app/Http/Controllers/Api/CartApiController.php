<?php

namespace App\Http\Controllers\Api;

use App\Api\CartApi;
use App\Api\Data\Cart\CartItem;
use App\Api\Data\ResponseData;
use App\Api\ResponseApi;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Paper;
use App\Models\PaperContent;
use Illuminate\Http\Request;
use Exception;

class CartApiController extends Controller implements CartApiControllerInterface
{
    protected $request;
    protected $cartApi;

    protected $responseApi;
    protected $responseData;

    function __construct(
        Request $request,
        CartApi $cartApi,
        ResponseApi $responseApi,
        ResponseData $responseData
    )
    {
        $this->request = $request;
        $this->cartApi = $cartApi;
        $this->responseApi = $responseApi;
        $this->responseData = $responseData;
    }

    /**
     * @return ResponseApi|object
     */
    function addToCart()
    {
        /**
         * @var PaperContent $product
         */
        $product = PaperContent::find($this->request->get('content_id'));
        /**
         * @var Paper $paper
         */
        $paper = $product->getPaper();
        try {
            if (!$paper){
                throw new Exception("this source not found", 400);
            }
            if (!$paper->getPrice()){
                throw new Exception("this source cant't add to cart", 400);
            }
            $cartData = $this->cartApi->addCart($paper, $this->request->get('qty'));
            return $this->responseApi->setResponse($this->responseData->setResponse($cartData));
        } catch (Exception $e) {
            return $this->responseApi->setStatusCode($e->getCode())->setResponse($this->responseData->setMessage($e->getMessage()));
        }
    }

    /**
     * @return ResponseApi
     */
    function getCart()
    {
        $cartData = $this->cartApi->getCart();
        return $this->responseApi->setResponse($this->responseData->setResponse($cartData));
    }

    /**
     * @return ResponseApi
     */
    function clearCart()
    {
        $this->cartApi->clearCart();
        return $this->responseApi->setResponse($this->responseData->setMessage('cart cleared!'));
    }

    /**
     * @param int $item_id
     * @return ResponseApi|object
     */
    public function removeItem($item_id)
    {
        $cartData = $this->cartApi->getCart();
        $listItems = $cartData->getItems();
        $checkItem = array_filter($listItems, function (CartItem $item) use($item_id){
            return $item->getValueId() === (int) $item_id;
        });
        if (!count($checkItem)){
            return $this->responseApi->setStatusCode(400)->setResponse($this->responseData->setMessage("removed item: '".current($checkItem)->getValueTitle()."'!"));
        }
        return $this->responseApi->setResponse($this->responseData->setResponse($this->cartApi->removeItem($item_id))->setMessage("removed item: '".current($checkItem)->getValueTitle()."'!"));
        // TODO: Implement removeItem() method.
    }

    /**
     * @return ResponseApi|object
     */
    public function submitOrder() {
        $cartData = $this->cartApi->getCart();
        if (count($cartData->getItems())) {
            return $this->responseApi->setResponse($this->responseData->setMessage("your cart none items"))->setStatusCode(400);
        }
        try {
            $orderData = $this->cartApi->submitOrder();
            if ($order_id = $orderData['order_id']) {
                $order = Order::find($order_id);
                return $this->responseApi->setResponse($this->responseData->setResponse($order));
            }
        } catch (\Throwable $th) {
            return $this->responseApi->setResponse($this->responseData->setMessage($th->getMessage()))->setStatusCode(500);
        }
    }
}
