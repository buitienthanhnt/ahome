<?php
namespace App\Api\Data\Paper;

use App\Api\Data\DataObject;
use Illuminate\Contracts\Support\Arrayable;

class Tag extends DataObject implements TagInterface, Arrayable
{
    public function setId(int $id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    function setEntityId(int $entity)
    {
        return $this->setData(self::ENTITY_ID, $entity);
    }

    function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    function setValue(string $value)
    {
        return $this->setData(self::VALUE, $value);
    }

    function getValue()
    {
        return $this->getData(self::VALUE);
    }

    function setBaseValue(string $value)
    {
        return $this->setData(self::BASE_VALUE, $value);
    }

    function getBaseValue()
    {
        return $this->getData(self::BASE_VALUE);
    }

    function setType(string $type)
    {
        return $this->setData(self::TYPE, $type);
    }

    function getType()
    {
        return $this->getData(self::TYPE);
    }
}
