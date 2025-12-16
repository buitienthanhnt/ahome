<?php
namespace App\Api\Data\Other;

use App\Api\Data\AttributeInterface;

interface HomeInfoInterface extends AttributeInterface{
	const TYPE = 'type';
	const DATA = 'data';

	/**
	 * @param string $type
	 * @return $this
	 */
	function setType($type);

	/**
	 * @return string
	 */
	function getType();

	/**
	 * @param $data
	 * @return $this
	 */
	function setDatas($data);

	/**
	 * @return
	 */
	function getDatas();
}
