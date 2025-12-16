<?php

namespace App\Http\Controllers\Api;

use App\Api\CategoryApi;
use App\Api\Data\ResponseData;
use App\Api\PaperRepository;
use App\Api\ResponseApi;
use App\Enums\CacheStorage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Thanhnt\Nan\Helper\CacheManager;

class CategoryApiController extends Controller implements CategoryApiControllerInterface
{

    protected $request;
    protected $responseData;
    protected $responseApi;

    protected $categoryApi;
    protected $paperRepository;

    protected $cacheManager;

    function __construct(
        Request $request,
        ResponseData $responseData,
        ResponseApi $responseApi,
        CategoryApi $categoryApi,
        PaperRepository $paperRepository,
        CacheManager $cacheManager
    )
    {
        $this->request = $request;
        $this->responseData = $responseData;
        $this->responseApi = $responseApi;
        $this->categoryApi = $categoryApi;
        $this->paperRepository = $paperRepository;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param int $category_id
     * @return ResponseApi
     */
    function getCategoryInfo(int $category_id)
    {
        $responseApi = $this->responseApi;
        return $responseApi->setResponse($this->responseData->setResponse($this->categoryApi->getCategoryById($category_id)));
    }

    /**
     * @return ApiResponse
     */
    public function getCategoryTree()
    {
        $responseApi = $this->responseApi;
        $responseApi->setResponse($this->responseData->setResponse($this->categoryApi->getCategoryTree()));
        return $responseApi;
    }

    /**
     * @return ApiResponse
     */
    public function getCategoryTop()
    {
        return $this->responseApi->setResponse($this->responseData->setResponse($this->categoryApi->getCategoryTop()));
    }

    /**
     * lấy bài viết theo thể loại.
     * @param int $category_id
     * @return ApiResponse
     */
    function getPaperCategory($category_id)
    {
        if (!is_numeric($category_id)) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->responseData->setMessage('category not found'));
        }
        $page = $this->request->get("page", 1);
        $limit = $this->request->get("limit", 12);
        $responseData = $this->cacheManager->getAndCache(
            CacheStorage::CATEGORY_PAPER . $category_id . "_" . $limit . "_" . $page,
            function () use ($category_id, $limit) {
                return $this->categoryApi->paperByCategory($category_id, $limit, false);
            },
            CacheStorage::TIME_3_H);
        return $this->responseApi->setResponse($this->responseData->setResponse($responseData));
    }
}
