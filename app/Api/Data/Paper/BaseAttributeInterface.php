<?php

namespace App\Api\Data\Paper;

use App\Api\Data\AttributeInterface;

interface BaseAttributeInterface extends AttributeInterface
{
    const ID = 'id';

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id);

    /**
     * @return int
     */
    public function getId();
}
