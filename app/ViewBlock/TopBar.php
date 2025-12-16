<?php
namespace App\ViewBlock;

use App\Api\CartApi;
use App\Services\CartService;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\View;

class TopBar implements Htmlable
{
    protected $template = 'frontend.templates.page_header';
    protected $cartApi;

    function __construct(
        CartApi $cartApi
    )
    {
        $this->cartApi = $cartApi;
    }

    function toHtml()
    {
        $cart = $this->cartApi->getCart();
        $top_menu_view = View::make($this->template)->with('cart_count', $cart->getCount())->render();
        return $top_menu_view;
    }
}
