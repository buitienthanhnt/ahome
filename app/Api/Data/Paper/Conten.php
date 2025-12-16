<?php
namespace App\Api\Data\Paper;

class Conten extends BaseAttribute implements ContenInterface
{
    function setType(string $type)
    {
        return $this->setData(self::TYPE, $type);
    }

    function getType()
    {
        return $this->getData(self::TYPE);
    }

    function setKey(string $key)
    {
        return $this->setData(self::KEY, $key);
    }

    function getKey()
    {
        return $this->getData(self::KEY);
    }

    function setDependValue(string $depend_value)
    {
        return $this->setData(self::DEPEND_VALUE, $depend_value);
    }

    function getDependValue()
    {
        return $this->getData(self::DEPEND_VALUE);
    }

    function setPaperId(int $paper_id)
    {
        return $this->setData(self::PAPER_ID, $paper_id);
    }

    function getPaperId()
    {
        return $this->getData(self::PAPER_ID);
    }

    function setValue(string $value)
    {
        return $this->setData(self::VALUE, $value);
    }

    function getValue()
    {
        return $this->getData(self::VALUE);
    }
}
