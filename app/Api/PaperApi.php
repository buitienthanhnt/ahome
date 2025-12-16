<?php

namespace App\Api;

use App\Api\Convert\ConvertPaper;
use App\Api\Data\Paper\PaperItem;
use App\Api\Data\ResponseData;
use App\Enums\CacheStorage;
use App\Helper\HelperFunction;
use App\Models\Paper;
use App\Models\PaperInterface;
use App\Models\ViewSource;
use App\Models\PaperTag;
use App\Models\PaperContent;
use App\Models\PaperContentInterface;
use App\Models\PaperTagInterface;
use App\Models\ViewSourceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Thanhnt\Nan\Helper\CacheManager;

class PaperApi extends BaseApi
{
    protected $helperFunction;
    protected $cacheManager;
    protected $request;
    /**
     * @var PaperContent $paperContent
     */
    protected $paperContent;

    protected $paperRepository;

    protected $paper;

    protected $responseData;
    protected $responseApi;

    protected $convertPaper;


    function __construct(
        HelperFunction $helperFunction,
        Request $request,
        PaperContent $paperContent,
        PaperRepository $paperRepository,
        Paper $paper,
        ResponseData $responseData,
        ResponseApi $responseApi,
        ConvertPaper $convertPaper,
        CacheManager $cacheManager
    ) {
        $this->helperFunction = $helperFunction;
        $this->request = $request;
        $this->paperContent = $paperContent;
        $this->paperRepository = $paperRepository;
        $this->paper = $paper;
        $this->responseData = $responseData;
        $this->responseApi = $responseApi;
        $this->convertPaper = $convertPaper;
        $this->cacheManager = $cacheManager;
    }

    /**
     * lấy các bài viết nhiều lượt xem nhất.
     * dựa trên viewSource
     * @return PaperItem[]
     */
    function mostPopulator()
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::PAPER_MOST_POPULATOR,
            function () {
                $mostView = ViewSource::where(ViewSourceInterface::ATTR_TYPE, ViewSource::TYPE_PAPER)
                    ->orderBy(ViewSourceInterface::ATTR_VALUE, 'DESC')
                    ->limit(8)
                    ->pluck(ViewSourceInterface::ATTR_SOURCE_ID);
                return $this->convertPaper->convertListData(Paper::find($mostView));
            },
            CacheStorage::TIME_30_M
        );
    }

    /**
     * lấy 8 bài viết tạo sau bài viết mới nhất.
     * @return PaperItem[]
     */
    function mostRecents()
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::PAPER_MOST_RECENTS,
            function () {
                $mostRecents = Paper::limit(8)->offset(1)->orderBy('created_at', 'DESC')->get();
                return $this->convertPaper->convertListData($mostRecents);
            },
            CacheStorage::TIME_30_M
        );
    }

    /**
     * lấy bài viết mới nhất.
     */
    function hit()
    {
        // $this->apiResponse->setMessage("demo for set message data");
        // $this->responseApi->setStatusCode(500)->setResponse($this->apiResponse);
        return $this->cacheManager->getAndCache(
            CacheStorage::PAPER_HIT,
            function () {
                return $this->convertPaper->convertItemData($this->paperRepository->hotNew());
            },
            CacheStorage::TIME_30_M
        );
    }

    /**
     * lấy 1 bài viết nhiều bình luận nhất.
     * @return PaperItem
     */
    function forward()
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::PAPER_FORWARD,
            function () {
                $maxCommentPaperId = $this->paperRepository->maxComment();
                if ($maxCommentPaperId) {
                    return $this->convertPaper->convertItemData($this->paperRepository->getById($maxCommentPaperId));
                }
                return null;
            },
            CacheStorage::TIME_3_H
        );
    }

    /**
     * get papers by list id.
     */
    function byIds(array $ids) {
        return $this->cacheManager->getAndCache(
            CacheStorage::PAPER_IDS.implode('-', $ids),
            function () use($ids) {
                $listPapers = Paper::find($ids);
                if ($listPapers) {
                    return $this->convertPaper->convertListData($listPapers);
                }
                return null;
            },
            CacheStorage::TIME_3_H
        );
    }

    /**
     * lấy 5 bài viết(image|sliderImage type)
     * @return PaperItem[]
     */
    function listImages()
    {
        try {
            return $this->cacheManager->getAndCache(
                CacheStorage::PAPER_LIST_IMAGE,
                function () {
                    return $this->convertPaper->convertListData($this->paper->find(
                        $this->paperContent->getByType([PaperContentInterface::TYPE_IMAGE, PaperContentInterface::TYPE_SLIDER])
                            ->select([PaperContentInterface::ATTR_PAPER_ID, "created_at"])
                            ->distinct()
                            ->limit(5)
                            ->orderBy('created_at', 'DESC')
                            ->pluck(PaperContentInterface::ATTR_PAPER_ID)
                            ->toArray()
                    ));
                },
                CacheStorage::TIME_3_H
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
        return null;
    }

    /**
     * lấy 8 bài viết kiểu timeline(sự kiện sắp diễn ra).
     */
    function timeLine()
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::PAPER_TIME_LINE,
            function () {
                $dt = Carbon::now('Asia/Ho_Chi_Minh');
                /**
                 * @var Collection $timeLine
                 */
                return $this->convertPaper->convertTimelineTree(
                    $this->paperContent
                        ->where(PaperContent::ATTR_TYPE, PaperContent::TYPE_TIMELINE)
                        ->where(PaperContent::ATTR_VALUE, ">=", $dt->toDateTimeString())
                        ->orderBy(PaperContent::ATTR_VALUE, 'ASC')
                        ->take(8)
                        ->get()
                );
            },
            CacheStorage::TIME_3_H
        );
    }

    /**
     * lấy 8 tags liên kết mới nhất.
     * @return string[]
     */
    function tags(): array
    {
        return $this->cacheManager->getAndCache(
            CacheStorage::PAPER_TAG,
            function () {
                return PaperTag::all()->unique(PaperTagInterface::ATTR_VALUE)->toQuery()
                    ->orderBy('id', 'DESC')->take(8)->pluck(PaperTagInterface::ATTR_VALUE)->toArray();
            },
            CacheStorage::TIME_1_H
        );
    }

    /**
     * lấy bài viết có liên quan danh mục.
     * @param int $paper_id
     * @return PaperItem[]
     */
    function getRelatedPaper(int $paper_id)
    {
        /**
         * @var Paper $paper
         */
        $paper = $this->paper->find($paper_id);
        if ($listRelated = $paper->getRelatedItems()) {
            return $this->convertPaper->convertListData($listRelated);
        }
        return null;
    }

    /**
     * lấy ngẫu nhiên 5 bài viết.
     * @return PaperItem[]
     */
    function getRandomPapers(int $limit = 5)
    {
        return $this->convertPaper->convertListData($this->paper->inRandomOrder()->limit($limit)->get());
    }

    /**
     * lấy data video mới nhất cho home.
     * @return array
     */
    function getLastVideo(bool $useData = false)
    {
        $video = $this->cacheManager->getAndCache(
            CacheStorage::PAPER_LAST_VIDEO,
            function () {
                return $this->paperContent->getByType([PaperContentInterface::TYPE_VIDEO])->orderBy('id', 'DESC')
                    ->limit(1)->get()->first();
            },
            CacheStorage::TIME_4_H
        );

        if (empty($video) || $useData) {
            return [
                "videoId" => "R4hcw8fXlQY",
                "height" => 220,
                "title" => "QUA NGÕ NHÀ EM - TẾT VẠN LỘC 2024!"
            ];
        }
        return [
            "videoId" => $video->{PaperContentInterface::ATTR_VALUE},
            "height" => 220,
            "title" => $video->{PaperContentInterface::ATTR_DEPEND_VALUE}
        ];
    }

    /**
     * tìm kiếm bài viết theo chuỗi gửi lên.
     * @return PaperItem[]
     */
    function searchAll()
    {
        $query = $this->request->get('search', $this->request->get('query'));
        return $this->cacheManager->getAndCache(
            CacheStorage::PAPER_SEARCH_API . $this->request->get('limit', 12) . "_" . $this->request->get("page", 1) . "_" . $query,
            function () use ($query) {
                return $this->convertPaper->convertPaperPaginate($this->search($query, true));
            },
            CacheStorage::TIME_6_H
        );
    }

    /**
     * quá trình tìm kiếm.
     * @param string $query
     * @return PaperItem[]
     */
    function search(string $query, $paginate = true)
    {
        $queryValue = strtolower($query);
        $searchPaper = Paper::where(PaperInterface::ATTR_TITLE, 'LIKE', "%$queryValue%")
            ->orWhere(PaperInterface::ATTR_SHORT_CONTENT, 'LIKE', "%$queryValue%")->pluck('id')->toArray() ?: [];

        $searchTags = PaperTag::where(PaperTagInterface::ATTR_VALUE, 'LIKE', "%$queryValue%")->pluck(PaperTagInterface::ATTR_ENTITY_ID)->toArray() ?: [];
        if ($paginate) {
            return Paper::whereIn('id', array_unique(array_merge($searchPaper, $searchTags)))->paginate($this->request->get('limit', 12));
        }
        return Paper::whereIn('id', array_unique(array_merge($searchPaper, $searchTags)))->get();
    }

    function listPapers()
    {
        $limit = $this->request->get('limit', 12);
        $page = $this->request->get("page", 1);
        return $this->cacheManager->getAndCache(
            CacheStorage::PAPER_LIST_API . $limit . "_" . $page . "_" . $this->request->get('type', ""),
            function () {
                return $this->convertPaper->convertPaperPaginate($this->paperRepository->paperAll());
            },
            CacheStorage::TIME_1_H
        );
    }
}
