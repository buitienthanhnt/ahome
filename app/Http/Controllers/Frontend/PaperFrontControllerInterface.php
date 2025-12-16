<?php
namespace App\Http\Controllers\Frontend;

interface PaperFrontControllerInterface{
    const CONTROLLER_NAME = 'Frontend\PaperFrontController';
    const PREFIX = 'paper';

    const FRONT_PAPER_ADD_LIKE = 'addPaperLike';
    const FRONT_PAPER_COMMENTS = 'getPaperComments';
    const FRONT_PAPER_ADD_COMMENT = 'addComment';
    const FRONT_PAPER_REPLY_COMMENT = 'replyComment';
    const FRONT_COMMENT_LIKE = 'commentLike';
    const FRONT_ADD_CART = 'addCart';
    const FRONT_VIEW_CART = 'viewCart';
    const FRONT_DELETE_CART_ITEM = 'deleteItem';
    const FRONT_CLEAR_CART = 'clearCart';
    const FRONT_CHECKOUT = 'checkout';
    const FRONT_CHECKOUT_POST = 'checkoutPost';
    const FRONT_PAPER_BY_TYPE = 'getPaperByType';
    const FRONT_MORE_PAPER_BY_TYPE = 'morePaperByType';

    /**
     * @param int $paper_id
     * @return mixed
     */
    public function addPaperLike(int $paper_id);

    /**
     * @param int $paper_id
     * @param int $p
     * @return mixed
     */
    public function getPaperComments(int $paper_id, int $p);

    /**
     * @param int $paper_id
     * @return mixed
     */
    public function addComment(int $paper_id);

    /**
     * @param int $comment_id
     * @return mixed
     */
    public function replyComment(int $comment_id);

    /**
     * @param int $comment_id
     * @return mixed
     */
    public function commentLike(int $comment_id);

    public function addCart();

    public function viewCart();

    /**
     * @param int $item_id
     * @return mixed
     */
    public function deleteItem(int $item_id);

    public function clearCart();

    public function checkout();

    public function checkoutPost();

    /**
     * @param string $type
     * @return mixed
     */
    public function getPaperByType(string $type);

    /**
     * @param string $type
     * @return mixed
     */
    public function morePaperByType(string $type);
}
