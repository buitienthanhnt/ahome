<?php

namespace App\Exceptions;

use App\Api\Data\ResponseData;
use App\Api\ResponseApi;
use Exception;

class ApiException extends Exception
{
    protected $dataValue;

    public function __construct($message = "", $code = 0, $previous = null, $dataValue = null)
    {
        $this->dataValue = $dataValue;
        parent::__construct($message, $code, $previous);
    }

    public function render() {
        /**
         * @var ResponseApi $response
         */
        $response = app()->make('App\Api\ResponseApi');
        /**
         * @var ResponseData $responseData
         */
        $responseData = app()->make('App\Api\Data\ResponseData');
        /**
         * update message
         */
        if ($this->getMessage()) {
            $responseData->setMessage($this->getMessage());
        }
        /**
         * update code
         */
        if ($this->getCode()) {
            $responseData->setCode($this->getCode());
        }

        /**
         * update response data value
         */
        if ($this->dataValue) {
            $responseData->setResponse($this->dataValue);
        }

        return $response->setStatusCode($this->getCode())->setResponse($responseData);
    }
}
