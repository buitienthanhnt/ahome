<?php

namespace App\Api\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;

class ResponseData extends DataObject implements ResponseDataInterface, Arrayable
{
	function __construct(Request $request)
	{
		$this->_data = [
			self::MESSAGE => null,
			self::RESPONSE => null,
			self::CODE => $request->get('code')
		];
	}
	function setMessage(string $message)
	{
		return $this->setData(self::MESSAGE, $message);
	}

	function getMessage()
	{
		return $this->getData(self::MESSAGE);
	}

	function setResponse($data)
	{
		return $this->setData(self::RESPONSE,  $data);
	}

	function getResponse()
	{
		return $this->getData(self::RESPONSE);
	}

	function setCode(int $code)
	{
		return $this->setData(self::CODE, $code);
	}

	function getCode()
	{
		return $this->getData(self::CODE);
	}
}
