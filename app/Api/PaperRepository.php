<?php

namespace App\Api;

use App\Api\Data\Page\PageInfo;
use App\Api\Data\Paper\PaperList;
use App\Enums\CacheStorage;
use App\Helper\HelperFunction;
use App\Models\Category;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Models\PaperInterface;
use App\Models\ViewSource;
use App\Models\ViewSourceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PaperRepository
{
    protected $request;

    protected $paper;
    protected $paperContent;
    protected $category;
    protected $paperList;
    protected $pageInfo;

    protected $helperFunction;

    function __construct(
        Request $request,
        Paper $paper,
        PaperContent $paperContent,
        Category $category,
        PaperList $paperList,
        PageInfo $pageInfo,
        HelperFunction $helperFunction
    )
    {
        $this->request = $request;
        $this->paper = $paper;
        $this->paperContent = $paperContent;
        $this->category = $category;
        $this->paperList = $paperList;
        $this->pageInfo = $pageInfo;
        $this->helperFunction = $helperFunction;
    }

    /**
     * @param int $id
     * @return Paper|null
     */
    function getById(int $id)
    {
        $response = null;
        if (Cache::has(CacheStorage::PAPER_DETAIL_MODEL . $id)) {
            $response = Cache::get(CacheStorage::PAPER_DETAIL_MODEL . $id);
        } else {
            try {
                /**
                 * @var Paper $detail
                 */
                $response = $this->paper->find($id);
                Cache::put(CacheStorage::PAPER_DETAIL_MODEL . $id, $response);
            } catch (\Throwable $th) {
            }
        }
        return $response;
    }


    public function getPaperbyIds(array $ids)
    {
        return $this->paper->find($ids);
    }

    function paperAll()
    {
        // support filter type.
        if ($filter = $this->request->get('type')) {
            // distinct là uniqui
            $paperIds = $this->paperContent->getByType(explode('&', $filter))->distinct()->pluck('paper_id');
            $papers = $this->paper->whereIn('id', $paperIds->toArray())->paginate($this->request->get('limit', 12));
            return $papers;
        }

		$papers = $this->paper->with("joinWriter")->paginate($this->request->get('limit', 12));
		return $papers;
	}

    /**
     * @return Paper
     */
    function lastest()
    {
        return $this->paper->all()->last();
    }

    /**
     * @return Paper
     */
    function hotNew()
    {
        return $this->paper->where(PaperInterface::ATTR_SHOW, '=', 1)->get()->first();
    }

    function maxComment()
    {
        try {
            $sql = 'SELECT paper_id FROM (SELECT comments.paper_id, COUNT(comments.paper_id) as Total FROM comments GROUP BY comments.paper_id) as Result ORDER BY Total DESC LIMIT 1';
            $data = DB::selectOne($sql);
            return $data->paper_id;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return null;
    }

    /**
     * @param int $category_id
     * @return PaperList
     */
    function getPaperByCategory(int $category_id, int $limit = 12)
    {
        /**
         * @var Category $category
         */
        $category = $this->category->find($category_id);
        return $category->getPaperByCategory($limit);
    }

    function mostPaperViews()
    {
        $mostView = ViewSource::where(ViewSourceInterface::ATTR_TYPE, ViewSource::TYPE_PAPER)
            ->orderBy(ViewSourceInterface::ATTR_VALUE, 'desc')
            ->limit(8)
            ->pluck(ViewSourceInterface::ATTR_SOURCE_ID)
            ->toArray();
        return $this->getPaperbyIds($mostView);
    }
}
