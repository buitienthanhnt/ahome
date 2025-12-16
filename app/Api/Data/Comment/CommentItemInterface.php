<?php
namespace App\Api\Data\Comment;

use App\Api\Data\AttributeInterface;

interface CommentItemInterface extends AttributeInterface{
    const ID = 'id';
    const NAME = 'name';
    const EMAIL = 'email';
    const CONTENT = 'content';
    const SHOW = 'show';
    const LIKE = 'like';
    const PARENT_ID = 'parentId';
    const PAPER_ID = 'paperId';
    const CHILDRENT_COUNT = 'childrentCount';

    /**
     * @param int $id
     * @return $this
     */
    function setId($id);

    /**
     * @return int
     */
    function getId();

    /**
     * @param string $name
     * @return $this
     */
    function setName($name);

    /**
     * @return string
     */
    function getName();

    /**
     * @param string $email
     * @return $this
     */
    function setEmail($email);

    /**
     * @return string
     */
    function getEmail();

    /**
     * @param int $content
     * @return $this
     */
    function setContent($content);

    /**
     * @return string
     */
    function getContent();

    /**
     * @param int $show
     * @return $this
     */
    function setShow($show);

    /**
     * @return bool
     */
    function getShow();

    /**
     * @param int $like
     * @return $this
     */
    function setLike($like);

    /**
     * @return int
     */
    function getLike();

    /**
     * @param int $parent_id
     * @return $this
     */
    function setparentId($parent_id);

    /**
     * @return int
     */
    function getParentId();

    /**
     * @param int $paper_id
     * @return $this
     */
    function setPaperId($paper_id);

    /**
     * @return int
     */
    function getPaperId();

    /**
     * @param int $childrent_count
     * @return $this
     */
    function setChildrentCount($childrent_count);

    /**
     * @return int
     */
    function getChildrentCount();
}