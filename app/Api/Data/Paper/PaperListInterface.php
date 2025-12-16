<?php
namespace App\Api\Data\Paper;

use App\Api\Data\Page\PageInfo;
use Illuminate\Contracts\Support\Arrayable;

interface PaperListInterface extends Arrayable{
	const ITEMS = 'items';
	const PAGE_INFO = 'pageInfo';

	/**
	 * @param PaperItem[] $items
	 * @return $this
	 */
	function setItems($items);

	/**
	 * @return PaperItem[]
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