<?php

namespace App\Api;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ResponseApi extends Response
{
    /**
     * Create a new HTTP response.
     *
     * @param mixed $content
     * @param int   $status
     * @param array $headers
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($content = '', $status = 200, array $headers = [])
    {
        parent::__construct($content, $status, $headers);
    }

    /**
     * Set the content on the response.
     *
     * @param mixed $responseData
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    function setResponse($responseData)
    {
        parent::setContent($responseData);
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    function setHeader(array $headers = []){
        $this->headers = new ResponseHeaderBag($headers);
        return $this;
    }
}
