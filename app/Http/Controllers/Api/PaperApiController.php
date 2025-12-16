<?php

namespace App\Http\Controllers\Api;

use App\Api\Convert\ConvertPaper;
use App\Api\Data\ResponseData;
use App\Api\PaperApi;
use App\Api\PaperRepository;
use App\Api\ResponseApi;
use App\Enums\CacheStorage;
use App\Events\ViewCount;
use App\Http\Controllers\Controller;
use App\Http\Exception\FormValidationException;
use App\Http\Validation\PaperLikeForm;
use App\Models\ViewSource;
use Illuminate\Http\Request;
use Thanhnt\Nan\Helper\CacheManager;

class PaperApiController extends Controller implements PaperApiControllerInterface
{
    protected $request;

    protected $paperRepository;
    protected $paperApi;

    protected $responseData;
    protected $responseApi;

    protected $paperLikeForm;

    protected $convertPaper;

    protected $cacheManager;

    function __construct(
        Request $request,
        ResponseData $responseData,
        ResponseApi $responseApi,
        PaperRepository $paperRepository,
        PaperApi $paperApi,
        PaperLikeForm $paperLikeForm,
        ConvertPaper $convertPaper,
        CacheManager $cacheManager
    )
    {
        $this->request = $request;
        $this->responseData = $responseData;
        $this->responseApi = $responseApi;
        $this->paperRepository = $paperRepository;
        $this->paperApi = $paperApi;
        $this->paperLikeForm = $paperLikeForm;
        $this->convertPaper = $convertPaper;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param int $paper_id
     * @return ResponseApi|object
     */
    function addPaperLike(int $paper_id)
    {
        $params = $this->request->toArray();
        try {
            $this->paperLikeForm->validate($params);
            $paperSource = ViewSource::where(ViewSource::ATTR_TYPE, ViewSource::TYPE_PAPER)->where(ViewSource::ATTR_SOURCE_ID, $paper_id)->first();
            if (empty($paperSource)) {
                ViewSource::firstOrCreate([
                    ViewSource::ATTR_TYPE => ViewSource::TYPE_PAPER, // type= paper|category(category chua ho tro.)
                    ViewSource::ATTR_SOURCE_ID => $paper_id,
                    ViewSource::ATTR_VALUE => 1,
                    ViewSource::ATTR_HEART => $params[ViewSource::PARAM_TYPE] === ViewSource::ATTR_HEART ? 1 : 0,
                    ViewSource::ATTR_LIKE => $params[ViewSource::PARAM_TYPE] === ViewSource::ATTR_HEART ? 1 : 0
                ]);
            } else {
                if ($params[ViewSource::PARAM_TYPE] === ViewSource::ATTR_LIKE) {
                    $paperSource->like = $params[ViewSource::PARAM_ACTION] === ViewSource::ACTION_VAL_ADD ? $paperSource->like + 1 : $paperSource->like - 1;
                } elseif ($params[ViewSource::PARAM_TYPE] === ViewSource::ATTR_HEART) {
                    $paperSource->heart = $params[ViewSource::PARAM_ACTION] === ViewSource::ACTION_VAL_ADD ? $paperSource->heart + 1 : $paperSource->heart - 1;
                }
                $paperSource->save();
            }
        } catch (FormValidationException $e) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->responseData->setMessage($e->getFullMessage()));
        } catch (\Throwable $th) {
            return $this->responseApi->setStatusCode($th->getCode())->setResponse($this->responseData->setMessage($th->getMessage()));
        }
        return $this->responseApi->setResponse($this->responseData->setMessage($params[ViewSource::PARAM_ACTION] === ViewSource::ACTION_VAL_ADD ? 'đã thích!!' : 'bỏ thích!!'));
    }

    /**
     * @param int $paper_id
     * @return ResponseApi|mixed|object
     */
    public function getPaperDetail(int $paper_id)
    {
        $paper = $this->paperRepository->getById($paper_id);
        if ($paper) {
            event(new ViewCount([
                "type" => "paper",
                "id" => $paper_id
            ]));
            $paperDetail = $this->cacheManager->getAndCache(
                CacheStorage::PAPER_LIST_API . $paper_id,
                function () use ($paper) {
                    return $this->convertPaper->convertPaperDetailApi($paper);
                },
                CacheStorage::TIME_3_H
            );
            // $result = \Spatie\ArrayToXml\ArrayToXml::convert($paperDetail->toArray(), 'root', true, 'UTF-8');
            // return $this->responseApi->setResponse($result)->header('Content-Type', 'text/xml');
            return $this->responseApi->setResponse($this->responseData->setResponse($paperDetail));
        }
        // throw new ApiException('demo for use response thow object!', 403, null, [
        //     "inputValue" => $paper_id,
        //     "maxInput" => 80
        // ]);
        return $this->responseApi->setStatusCode(400)->setResponse($this->responseData->setMessage('thông tin yêu cầu không tồn tại!'));
    }

    /**
     * @return ResponseApi
     */
    public function listPapers()
    {
        $responseApi = $this->responseApi;
        $responseApi->setResponse($this->responseData->setResponse($this->paperApi->listPapers()));
        return $responseApi;
    }

    /**
     * @param int $paper_id
     * @return ApiResponse
     */
    public function getRelatedPaper(int $paper_id)
    {
        return $this->responseApi->setResponse($this->responseData->setResponse($this->paperApi->getRelatedPaper($paper_id)));
    }

    public function getRandomPaper() {
        return $this->responseApi->setResponse($this->responseData->setResponse($this->paperApi->getRandomPapers(6)))->setStatusCode(200);
    }

    function getPaperByIds() {
        $list_id = (array_filter(explode(',', $this->request->get('ids'))));
        return $this->responseApi->setResponse($this->responseData->setResponse($this->paperApi->byIds($list_id)))->setStatusCode(200);
    }
}
