<?php
namespace App\Api\Data\Paper;

use App\Api\Data\DataObject;

class PaperList extends DataObject implements PaperListInterface
{
	function setItems($items)
	{
		return $this->setData(self::ITEMS, $items);
	}

	function getItems()
	{
		return $this->getData(self::ITEMS);
	}

	function setPageInfo($page_info)
	{
		return $this->setData(self::PAGE_INFO, $page_info);
	}

	function getPageInfo()
	{
		return $this->getData(self::PAGE_INFO);
	}
}
