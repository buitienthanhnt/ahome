<?php
namespace App\Api\Data\Category;

use App\Api\Data\Attribute;

class CategoryItem extends Attribute implements CategoryItemInterface{

    public function setId(int $id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @param string $name
     * @return mixed
     */
    function setName(string $name)
    {
        return $this->setData(self::NAME, $name);
        // TODO: Implement setName() method.
    }

    /**
     * @return mixed
     */
    function getName()
    {
        return $this->getData(self::NAME);
        // TODO: Implement getName() method.
    }

    /**
     * @param string $url
     * @return mixed
     */
    function setUrl(string $url)
    {
        return $this->setData(self::URL, $url);
        // TODO: Implement setUrl() method.
    }

    /**
     * @return mixed
     */
    function getUrl()
    {
        return $this->getData(self::URL);
        // TODO: Implement getUrl() method.
    }

    /**
     * @param bool $active
     * @return mixed
     */
    function setActive(bool $active)
    {
        return $this->setData(self::ACTIVE, $active);
        // TODO: Implement setActive() method.
    }

    /**
     * @return mixed
     */
    function getActive()
    {
        return $this->getData(self::ACTIVE);
        // TODO: Implement getActive() method.
    }

    /**
     * @param string $type
     * @return mixed
     */
    function setType(string $type)
    {
        return $this->setData(self::TYPE, $type);
        // TODO: Implement setType() method.
    }

    /**
     * @return mixed
     */
    function getType()
    {
        return $this->getData(SELF::TYPE);
        // TODO: Implement getType() method.
    }

    /**
     * @param int $parent_id
     * @return mixed
     */
    function setParentId(int $parent_id)
    {
        return $this->setData(self::PARENT_ID, $parent_id);
        // TODO: Implement setParentId() method.
    }

    /**
     * @return mixed
     */
    function getParentId()
    {
        return $this->getData(self::PARENT_ID);
        // TODO: Implement getParentId() method.
    }

    /**
     * @param $childrents
     * @return mixed
     */
    function setChildrents($childrents)
    {
        return $this->setData(self::CHILDRENTS, $childrents);
        // TODO: Implement setChildrents() method.
    }

    /**
     * @return mixed
     */
    function getChildrents()
    {
        return $this->getData(self::CHILDRENTS);
        // TODO: Implement getChildrents() method.
    }

    /**
     * @param string|null $image_path
     * @return mixed
     */
    function setImagePath($image_path)
    {
        return $this->setData(self::IMAGE_PARH, $image_path);
        // TODO: Implement setImagePath() method.
    }

    /**
     * @return mixed
     */
    function getImagePath()
    {
        return  $this->getData(self::IMAGE_PARH);
        // TODO: Implement getImagePath() method.
    }
}
