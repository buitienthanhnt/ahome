<?php

namespace App\Api\Convert;

use App\Api\Data\Other\TimeLineItem;
use App\Api\Data\Page\PageInfo;
use App\Api\Data\Paper\Conten;
use App\Api\Data\Paper\Info;
use App\Api\Data\Paper\PaperDetail;
use App\Api\Data\Paper\PaperItem;
use App\Api\Data\Paper\PaperList;
use App\Api\WriterRepository;
use App\Helper\HelperFunction;
use App\Helper\Nan;
use App\Models\Category;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Models\PaperContentInterface;
use App\Models\PaperInterface;
use App\Models\PaperTag;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class ConvertPaper extends ConvertPagination
{
    use Nan;

    protected $paper;
    protected $category;
    protected $paperList;
    protected $pageInfo;
    protected $writerRepository;
    protected $convertWriter;
    protected $convertTag;

    protected $helperFunction;

    function __construct(
        Paper $paper,
        Category $category,
        PaperList $paperList,
        PageInfo $pageInfo,
        HelperFunction $helperFunction,
        WriterRepository $writerRepository,
        ConvertWriter $convertWriter,
        ConvertTag $convertTag
    )
    {
        $this->paper = $paper;
        $this->category = $category;
        $this->paperList = $paperList;
        $this->pageInfo = $pageInfo;
        $this->helperFunction = $helperFunction;
        $this->writerRepository = $writerRepository;
        $this->convertWriter = $convertWriter;
        $this->convertTag = $convertTag;
    }

    /**
     * @param PaperTag[] $tags
     * @return array
     */
    function convertTags($tags)
    {
        return $this->convertTag->convertListData($tags);
    }

    /**
     * @param Paper[] $data
     * @return PaperItem[]
     */
    function formatSug($data)
    {
        $paperItems = [];
        foreach ($data as $item) {
            $paperItems[] = $this->convertItemData($item);
        }
        return $paperItems;
    }

    function convertSliderdata(string $sliderJsons)
    {
        $sliders = json_decode($sliderJsons, true);
        foreach ($sliders as &$value) {
            $value['value'] = $this->getImageUrl($value['image_path']);
        }
        return json_encode($sliders) ?: $sliderJsons;
    }

    /**
     * @param \App\Models\PaperContent[] $contens
     */
    protected function covertContentData($contens)
    {
        if (empty($contens)) {
            return null;
        }
        $return_data = [];
        foreach ($contens as $value) {
            $conten = new Conten();
            $conten->setId($value->id);
            $conten->setType($value->{PaperContentInterface::ATTR_TYPE});
            $conten->setKey($value->{PaperContentInterface::ATTR_KEY});
            $conten->setDependValue($value->{PaperContentInterface::ATTR_DEPEND_VALUE} ?: '');
            $conten->setPaperId($value->{PaperContentInterface::ATTR_PAPER_ID});
            switch ($value->{PaperContentInterface::ATTR_TYPE}) {
                case PaperContentInterface::TYPE_IMAGE:
                    $conten->setValue($this->helperFunction->replaceImageUrl($value->getImagePath() ?: ''));
                    break;
                case PaperContentInterface::TYPE_SLIDER:
                    $conten->setValue($this->convertSliderdata($value->{PaperContentInterface::ATTR_VALUE}));
                    break;
                default:
                    $conten->setValue($value->{PaperContentInterface::ATTR_VALUE});
            }
            $return_data[] = $conten;
        }
        return $return_data;
    }

    /**
     * @param Paper $paper
     * @return \App\Api\Data\Paper\Info
     */
    protected function convertPaperInfo($paper)
    {
        /**
         * setInfo data
         */
        $info = new Info();
        $info->setViewCount($paper->viewCount());
        $info->setCommentCount($paper->commentCount());
        $info->setLike($paper->paperLike());
        $info->setHeart($paper->paperHeart());
        return $info;
    }

    /**
     * @param Paper $paper
     * @return PaperDetail
     */
    function convertPaperDetailApi($paper)
    {
        $response = new PaperDetail();
        $response->setId($paper->id);
        $response->setTitle($paper->{PaperInterface::ATTR_TITLE});
        $response->setCreatedAt($paper->created_at);
        $response->setUpdatedAt($paper->getCreatedAt());
        $response->setShortContent($paper->{PaperInterface::ATTR_SHORT_CONTENT});
        $response->setImage($paper->getImagePath());
        $response->setUrl($paper->getUrl());
        $response->setActive($paper->{PaperInterface::ATTR_ACTIVE});
        $response->setContents($this->covertContentData($paper->getContents()));
        $response->setSuggest($this->formatSug(Paper::all()->random(4)));
        $response->setTags($this->convertTags($paper->getTags()));
        $response->setInfo($this->convertPaperInfo($paper));
        return $response;
    }

    /**
     * @param Paper $paper
     * @return PaperItem
     */
    function convertItemData($item)
    {
        /**
         * @var Paper $item
         */
        $paperItem = new PaperItem();
        $paperItem->setId($item->id);
        $paperItem->setUrl($item->getUrl());
        $paperItem->setActive(PaperInterface::ATTR_ACTIVE);
        $paperItem->setCreatedAt($item->getCreatedAt());
        $paperItem->setImage($item->getImagePath());
        $paperItem->setShortContent($item->{PaperInterface::ATTR_SHORT_CONTENT});
        $paperItem->setTitle($item->{PaperInterface::ATTR_TITLE});
        $paperItem->setShowHot($item->{PaperInterface::ATTR_SHOW});
        $paperItem->setInfo($this->convertPaperInfo($item));
        $paperItem->setWriter($this->convertWriter->convertItemData($item->getWriter()));
        return $paperItem;
    }

    /**
     * @param LengthAwarePaginator $paginateDatas
     * @return PaperList
     */
    function convertPaperPaginate($paginateDatas)
    {
        $paperList = $this->paperList;
        $paperList->setItems($this->convertListData($paginateDatas));
        $paperList->setPageInfo($this->convertPageInfo($paginateDatas));
        return $paperList;
    }

    /**
     * @param PaperContent $item
     */
    function convertTimeLineItem($item)
    {
        $value = new TimeLineItem();
        $paper = $item->getPaper();
        $value->setId($paper->id);
        $value->setTitle($paper->{PaperInterface::ATTR_TITLE});
        $value->setTime(date_format(new DateTime($item->value), "d-m-Y"));
        $value->setDescription(
    $paper->{PaperInterface::ATTR_TITLE} !== $paper->{PaperInterface::ATTR_SHORT_CONTENT} ?
                $paper->{PaperInterface::ATTR_SHORT_CONTENT}
                : null
        );
        return $value;
    }

    /**
     * @param Illuminate\Database\Eloquent\Collection|array $items
     */
    function convertTimelineTree($items = [])
    {
        $listItems = [];
        foreach ($items as $item) {
            $listItems[] = $this->convertTimeLineItem($item);
        }
        return $listItems;
    }
}
