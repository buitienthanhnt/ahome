<?php
namespace App\Api\Data\Other;

use App\Api\Data\Attribute;

class HomeInfo extends Attribute implements HomeInfoInterface
{
	function setType($type)
    {
        // TODO: Implement setHit() method.
        return $this->setData(self::TYPE, $type);
    }

    function getType()
    {
        return $this->getData(self::TYPE);
        // TODO: Implement getHit() method.
    }

    public function setDatas($data)
    {
        return $this->setData(self::DATA, $data);
        // TODO: Implement setForward() method.
    }

    public function getDatas()
    {
        return $this->getData(self::DATA);
        // TODO: Implement getForward() method.
    }
}
