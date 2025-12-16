<?php
namespace App\Api\Data\Tag;

use App\Api\Data\Page\PageInfo;
use Illuminate\Contracts\Support\Arrayable;

interface TagListInterface extends Arrayable{
    const ITEMS = 'items';
    const PAGE_INFO = 'pageInfo';

    /**
     * @param Tag[] $items
     * @return $this
     */
    function setItems($items);

    /**
     * @return Tag[]
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
