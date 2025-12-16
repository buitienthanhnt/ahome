<?php

namespace App\Api\Data\User;

use App\Api\Data\AttributeInterface;

interface UserInfoInterface extends AttributeInterface
{
    const ID = 'id';
    const NAME = 'name';
    const EMAIL = 'email';

    /**
     * @param int $id
     * @return $this
     */
    function setId($id);

    /**
     * 
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
}
