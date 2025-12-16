<?php
namespace App\Api\Data\User;

use App\Api\Data\Attribute;

class UserInfo extends Attribute implements UserInfoInterface
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
}
