<?php

namespace App\Http\Controllers\Api;

use App\Api\Data\ResponseData;
use App\Api\ResponseApi;
use App\Api\WriterApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WriterApiController extends Controller implements WriterApiControllerInterface
{

    protected $request;
    protected $responseData;
    protected $responseApi;

    protected $writerApi;

    function __construct(
        Request $request,
        ResponseData $responseData,
        ResponseApi $responseApi,
        WriterApi $writerApi
    ) {
        $this->request = $request;
        $this->responseData = $responseData;
        $this->responseApi = $responseApi;
        $this->writerApi = $writerApi;
    }

    /**
     * @param int $writer_id
     * @return ResponseApi
     */
    public function getPaperByWriter(int $writer_id)
    {
        return $this->responseApi->setResponse($this->responseData->setResponse($this->writerApi->getPapers($writer_id)));
    }

    /**
     * @return ApiResponse
     */
    public function getWriterList()
    {
        // TODO: Implement getWriterList() method.
        return $this->responseApi->setResponse($this->responseData->setResponse($this->writerApi->listWriter()));
    }
}
