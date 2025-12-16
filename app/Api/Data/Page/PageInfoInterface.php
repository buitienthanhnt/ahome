<?php
namespace App\Api\Data\Page;

use Illuminate\Contracts\Support\Arrayable;

interface PageInfoInterface extends Arrayable{
	const CURRENT_PAGE = 'currentPage';
	const LAST_PAGE = 'lastPage';
	const TOTAL = 'total';
	const PAGE_NUMBER = 'pageNumber';

	/**
	 * @param int $current_page
	 * @return $this
	 */
	function setCurrentPage(int $current_page);

	/**
	 * @return int
	 */
	function getCurrentPage();

	/**
	 * @param int $last_page
	 * @return $this
	 */
	function setLastPage(int $last_page);

	/**
	 * @return int
	 */
	function getLastPage();

	/**
	 * @param int $total
	 * @return $this
	 */
	function setTotal(int $total);

	/**
	 * @return int
	 */
	function getTotal();

	/**
	 * @param int $page_number
	 * @return $this
	 */
	function setPageNumber(int $page_number);

	/**
	 * @return int
	 */
	function getPageNumber();
}