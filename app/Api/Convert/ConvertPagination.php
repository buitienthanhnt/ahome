<?php

namespace App\Api\Convert;

use App\Api\Data\Page\PageInfo;
use Illuminate\Database\Eloquent\Model;

abstract class ConvertPagination
{
    /**
     * @param Model $item
     * @return DataObject
     */
    abstract function convertItemData($item);

    /**
     * @param $paginateDatas
     * @return PageInfo
     */
    public function convertPageInfo($paginateDatas)
    {
        $pageInfo = new PageInfo();
        $pageInfo->setCurrentPage($paginateDatas->currentPage());
        $pageInfo->setLastPage($paginateDatas->lastPage());
        $pageInfo->setPageNumber($paginateDatas->perPage());
        $pageInfo->setTotal($paginateDatas->total());
        return $pageInfo;
    }

    public function convertListData($listItems = [])
    {
        $items = [];
        if (!empty($listItems)) {
            foreach ($listItems as $item) {
                $items[] = $this->convertItemData($item);
            }
            return $items;
        }
        return [];
    }
}
