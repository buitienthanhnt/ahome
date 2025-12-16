<?php
namespace App\Api\Data\Paper;

use App\Api\Data\Attribute;

class BaseAttribute extends Attribute implements BaseAttributeInterface
{
    public function setId(int $id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }
}

?>