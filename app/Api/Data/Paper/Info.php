<?php
namespace App\Api\Data\Paper;

use App\Api\Data\DataObject;

class Info extends DataObject implements InfoInterface {
    function setViewCount(int $view_count)
    {
        return $this->setData(self::VIEW_COUNT, $view_count);
    }

    function getViewCount()
    {
        return $this->getData(self::VIEW_COUNT);
    }

    function setCommentCount(int $comment_count)
    {
        return $this->setData(self::COMMENT_COUNT, $comment_count);
    }

    function getCommentCount()
    {
        return $this->getData(self::COMMENT_COUNT);
    }

    function setLike(int $like)
    {
        return $this->setData(self::LIKE, $like);
    }

    function getLike()
    {
        return $this->getData(self::LIKE);
    }

    function setHeart(int $heart)
    {
        return $this->setData(self::HEART, $heart);
    }

    function getHeart()
    {
        return $this->getData(self::HEART);
    }
}