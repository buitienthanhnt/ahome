<?php
namespace App\Api\Data\Page;

use App\Api\Data\DataObject;

class PageInfo extends DataObject implements PageInfoInterface
{
	function setCurrentPage(int $current_page)
	{
		return $this->setData(self::CURRENT_PAGE, $current_page);
	}

	function getCurrentPage()
	{
		return $this->getData(self::CURRENT_PAGE);
	}

	function setLastPage(int $last_page)
	{
		return $this->setData(self::LAST_PAGE, $last_page);
	}

	function getLastPage()
	{
		return $this->getData(self::LAST_PAGE);
	}

	function setTotal(int $total)
	{
		return $this->setData(self::TOTAL, $total);
	}

	function getTotal()
	{
		return $this->getData(self::TOTAL);
	}

	function setPageNumber(int $page_number)
	{
		return $this->setData(self::PAGE_NUMBER, $page_number);
	}

	function getPageNumber()
	{
		return $this->getData(self::PAGE_NUMBER);
	}
}
