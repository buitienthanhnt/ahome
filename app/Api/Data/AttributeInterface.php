<?php

namespace App\Api\Data;

use Illuminate\Contracts\Support\Arrayable;

interface AttributeInterface extends Arrayable
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt(string $created_at);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $updated_at
     * @return $this
     */
    public function setUpdatedAt(string $updated_at);

    /**
     * @return string
     */
    public function getUpdatedAt();
}
