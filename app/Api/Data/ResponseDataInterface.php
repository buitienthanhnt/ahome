<?php
namespace App\Api\Data;

interface ResponseDataInterface {
	const MESSAGE = 'message';
	const RESPONSE = 'response';
	const CODE = 'code';

	/**
	 * @param string $meaage
	 * @return $this
	 */
	function setMessage(string $message);

	/**
	 * @return string
	 */
	function getMessage();

	/**
	 * @param mixed $data
	 * @return $this
	 */
	function setResponse($data);

	/**
	 * @return Arrayable
	 */
	function getResponse();

	/**
	 * @param int $code
	 * @return $this
	 */
	function setCode(int $code);

	/**
	 * @return int
	 */
	function getCode();
}
