<?php

namespace App\Api\Data\Category;

use App\Api\Data\AttributeInterface;

interface CategoryItemInterface extends AttributeInterface
{
    const ID = 'id';
    const NAME = 'name';
    const URL = 'url';
    const ACTIVE = 'active';
    const TYPE = 'type';
    const IMAGE_PARH = 'image';
    const PARENT_ID = 'parentId';
    const CHILDRENTS = 'childrents';

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $name
     * @return mixed
     */
    function setName(string $name);

    /**
     * @return mixed
     */
    function getName();

    /**
     * @param string $url
     * @return mixed
     */
    function setUrl(string $url);

    /**
     * @return mixed
     */
    function getUrl();

    /**
     * @param bool $active
     * @return mixed
     */
    function setActive(bool $active);

    /**
     * @return mixed
     */
    function getActive();

    /**
     * @param string $type
     * @return mixed
     */
    function setType(string $type);

    /**
     * @return mixed
     */
    function getType();

    /**
     * @param string|null $image_path
     * @return mixed
     */
    function setImagePath($image_path);

    /**
     * @return mixed
     */
    function getImagePath();

    /**
     * @param int $parent_id
     * @return mixed
     */
    function setParentId(int $parent_id);

    /**
     * @return mixed
     */
    function getParentId();

    /**
     * @param $childrents
     * @return mixed
     */
    function setChildrents($childrents);

    /**
     * @return mixed
     */
    function getChildrents();
}
