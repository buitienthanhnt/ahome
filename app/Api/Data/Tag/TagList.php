<?php

namespace App\Api\Data\Tag;

use App\Api\Data\DataObject;

class TagList extends DataObject implements TagListInterface{

    public function setItems($items)
    {
        // TODO: Implement setItems() method.
        return $this->setData(self::ITEMS, $items);
    }

    public function getItems()
    {
        // TODO: Implement getItems() method.
        return $this->getData(self::ITEMS);
    }

    public function setPageInfo($page_info)
    {
        // TODO: Implement setPageInfo() method.
        return $this->setData(self::PAGE_INFO, $page_info);
    }

    public function getPageInfo()
    {
        // TODO: Implement getPageInfo() method.
        return $this->getData(self::PAGE_INFO);
    }
}
