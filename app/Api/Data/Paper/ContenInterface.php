<?php

namespace App\Api\Data\Paper;

interface ContenInterface extends BaseAttributeInterface
{
    const TYPE = "type";
    const VALUE = "value";
    const KEY = "key";
    const DEPEND_VALUE = "dependValue";
    const PAPER_ID = "paperId";

    /**
     * @param string $type
     * @return $this
     */
    function setType(string $type);

    /**
     * @return string
     */
    function getType();

    
    /**
     * @param string $key
     * @return $this
     */
    function setKey(string $key);

    /**
     * @return string
     */
    function getKey();

    /**
     * @param string $depend_value
     * @return $this
     */
    function setDependValue(string $depend_value);

    /**
     * @return string
     */
    function getDependValue();

    /**
     * @param int $paper_id
     * @return $this
     */
    function setPaperId(int $paper_id);

    /**
     * @return int
     */
    function getPaperId();

    /**
     * @param string $value
     * @return $this
     */
    function setValue(string $value);

    /**
     * @return string
     */
    function getValue();
}
