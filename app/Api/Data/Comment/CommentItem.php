<?php
namespace App\Api\Data\Comment;

use App\Api\Data\Attribute;

class CommentItem extends Attribute implements CommentItemInterface
{
    function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    function getId()
    {
        return $this->getData(self::ID);
    }

    function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    function getName()
    {
        return $this->getData(self::NAME);
    }

    function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    function setShow($show)
    {
        return $this->setData(self::SHOW, $show);
    }

    function getShow()
    {
        return $this->getData(self::SHOW);
    }

    function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    function setLike($like)
    {
        return $this->setData(self::LIKE, $like);
    }

    function getLike()
    {
        return $this->getData(self::LIKE);
    }

    function setPaperId($paper_id)
    {
        return $this->setData(self::PAPER_ID, $paper_id);
    }

    function getPaperId()
    {
        return $this->getData(self::PAPER_ID);
    }

    function setparentId($parent_id)
    {
        return $this->setData(self::PARENT_ID, $parent_id);
    }

    function getParentId()
    {
        return $this->getData(self::PARENT_ID);
    }

    function setChildrentCount($childrent_count)
    {
        return $this->setData(self::CHILDRENT_COUNT, $childrent_count);
    }

    function getChildrentCount()
    {
        return $this->getData(self::CHILDRENT_COUNT);
    }
}
