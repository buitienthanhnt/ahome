<?php

namespace App\Api\Convert;

use App\Api\Data\Category\CategoryItem;
use App\Api\Data\Comment\CommentList;
use App\Models\CategoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ConvertCategory extends ConvertPagination
{

	/**
	 * @param \App\Models\Category $category
	 * @return CategoryItem
	 */
	function convertItemData($category, $with_children = true)
	{
		$_category = new CategoryItem();
		$_category->setName($category->{CategoryInterface::ATTR_NAME});
		$_category->setId($category->id);
		$_category->setActive($category->{CategoryInterface::ATTR_ACTIVE});
		$_category->setType($category->{CategoryInterface::ATTR_TYPE});
		$_category->setImagePath($category->getImagepath());
		$_category->setParentId($category->{CategoryInterface::ATTR_PARENT_ID});
		$_category->setUrl($category->getUrl());
		if ($with_children) {
			$_category->setChildrents($this->convertCategoryChildrent($category->getChildrent, $with_children));
		}
		return $_category;
	}

	/**
	 * @param Category[] $childrents
	 * @return CategoryItemInterface[]
	 */
	function convertCategoryChildrent($childrents, $with_children = true)
	{
		$_childrentData = [];
		foreach ($childrents as $item) {
			$_childrentData[] = $this->convertItemData($item, $with_children);
		}
		return $_childrentData;
	}

	/**
	 * @param LengthAwarePaginator $paginateDatas
	 */
	function convertPaginate($paginateDatas)
	{
		$commentList = new CommentList();
		$commentList->setItems($this->convertListData($paginateDatas->items()));
		$commentList->setPageInfo($this->convertPageInfo($paginateDatas));
		return $commentList;
	}
}
