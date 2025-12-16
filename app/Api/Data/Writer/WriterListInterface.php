<?php
namespace App\Api\Data\Writer;

use App\Api\Data\AttributeInterface;
use App\Api\Data\Page\PageInfo;

interface WriterListInterface extends AttributeInterface{
    const ITEMS = 'items';
    const PAGE_INFO = 'pageInfo';

    /**
     * @param WriterItem[] $items
     * @return $this
     */
    function setItems($items);

    /**
     * @return WriterItem[]
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
