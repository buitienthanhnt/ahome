<?php

namespace App\Api\Data\Other;

use App\Api\Data\Attribute;

class TimeLineItem extends Attribute implements TimeLineItemInterface
{

    function setId(int $id)
    {
        return $this->setData(self::ID, $id);
    }

    function getId()
    {
        return $this->getData(self::ID);
    }

    public function setTitle(string $title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    public function setTime(string $time)
    {
        return $this->setData(self::TIME, $time);
    }

    public function getTime()
    {
        return $this->getData(self::TIME);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }
}
