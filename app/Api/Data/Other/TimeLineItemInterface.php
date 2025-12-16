<?php

namespace App\Api\Data\Other;

use App\Api\Data\AttributeInterface;

interface TimeLineItemInterface extends AttributeInterface{
    const ID = 'id';
    const TITLE = 'title';
    const TIME = 'time';
    const DESCRIPTION = 'description';

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id);

    /**
     * @return number
     */
    public function getId();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title);

    /**
     * @return string
     */
    public function getTitle();

     /**
     * @param string $time
     * @return $this
     */
    public function setTime(string $time);

    /**
     * @return string
     */
    public function getTime();

     /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getDescription();
}
