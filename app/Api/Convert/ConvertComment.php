<?php

namespace App\Api\Convert;

use App\Api\Data\Comment\CommentItem;
use App\Api\Data\Comment\CommentList;
use App\Models\Comment;
use App\Models\CommentInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ConvertComment extends ConvertPagination
{

	function __construct() {}

	/**
	 * @param Comment $comment
	 */
	function convertItemData($item)
	{
		$commentItem = new CommentItem();
		$commentItem->setId($item->id);
		$commentItem->setName($item->{CommentInterface::ATTR_NAME});
		$commentItem->setContent($item->{CommentInterface::ATTR_CONTENT});
		$commentItem->setEmail($item->{CommentInterface::ATTR_EMAIL});
		$commentItem->setChildrentCount($item->getChildrentCount());
		$commentItem->setPaperId($item->{CommentInterface::ATTR_PAPER_ID});
		return $commentItem;
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
