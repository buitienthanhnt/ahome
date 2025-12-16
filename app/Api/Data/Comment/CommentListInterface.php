<?php
namespace App\Api\Data\Comment;

use App\Api\Data\AttributeInterface;

interface CommentListInterface extends AttributeInterface{
    const ITEMS = 'items';
    const PAGE_INFO = 'pageInfo';

        /**
     * @param CommentItem[] $items
     * @return $this
     */
    function setItems($items);

    /**
     * @return CommentItem[]
     */
    function getItems();

    /**
     * @param PageInfo $page_info
     * @return $this
     */
    function setPageInfo($page_info);

    /**
     * @return PageInfo
     */
    function getPageInfo();
}