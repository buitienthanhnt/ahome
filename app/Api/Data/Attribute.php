<?php
namespace App\Api\Data;

class Attribute extends DataObject implements AttributeInterface
{
    function setCreatedAt(string $created_at)
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }

    function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    function setUpdatedAt(string $updated_at)
    {
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

    function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }
}

?>
