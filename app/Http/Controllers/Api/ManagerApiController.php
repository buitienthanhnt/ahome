<?php

namespace App\Http\Controllers\Api;

use App\Api\CategoryApi;
use App\Api\Convert\ConvertTag;
use App\Api\Data\DataObject;
use App\Api\Data\Other\HomeInfo;
use App\Api\Data\ResponseData;
use App\Api\Data\Tag\TagList;
use App\Api\PaperApi;
use App\Api\ResponseApi;
use App\Api\WriterApi;
use App\Models\PaperTag;
use App\Models\PaperTagInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Thanhnt\Nan\Helper\DomHtml;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\StringHelper;

// https://www.php.net/manual/en/langref.php php
class ManagerApiController extends Controller implements ManagerApiInterface
{
    use DomHtml;
    use StringHelper;

    protected $request;
    protected $paperApi;
    protected $categoryApi;
    protected $writerApi;
    protected $responseData;
    protected $responseApi;

    protected $convertTag;


    public function __construct(
        Request $request,
        PaperApi $paperApi,
        CategoryApi $categoryApi,
        ResponseData $responseData,
        ResponseApi $responseApi,
        WriterApi $writerApi,
        ConvertTag $convertTag
    ) {
        $this->request = $request;
        $this->writerApi = $writerApi;
        $this->responseData = $responseData;
        $this->categoryApi = $categoryApi;
        $this->paperApi = $paperApi; // new for api
        $this->responseApi = $responseApi;
        $this->convertTag = $convertTag;
    }

    /**
     * @return array|mixed
     */
    protected function chart()
    {
        $thoi_tiet_hn_data = "thoi_tiet_hn_data";
        if (Cache::has($thoi_tiet_hn_data)) {
            return Cache::get($thoi_tiet_hn_data);
        }
        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast?q=Hanoi,vn&APPID=c9e745ee0f5c9ec4896c82160827da22&lang=vi');
        $listData = array_splice($response->object()->list, 0, 8);
        $labels = [];
        $datasets = [];
        foreach ($listData as $datum) {
            $labels[] = Carbon::createFromTimestamp($datum->dt, 'Asia/Ho_Chi_Minh')->format('H:i');
            $datasets[] = number_format($datum->main->temp - 273.15, 1);
        }

        $lineMap = [
            "data" => [
                "labels" => $labels,
                "datasets" => [
                    [
                        "data" => $datasets
                    ]
                ]
            ],
            "yAxisLabel" => "",
            "yAxisSuffix" => '°C',
            "bezier" => true,
            "yAxisInterval" => 1,
            "chartConfig" => [
                "backgroundColor" => "#e26a00",
                "backgroundGradientFrom" => "#ff7cc0", // 82baff
                "backgroundGradientTo" => "#82baff",   // ffa726
                "decimalPlaces" => 1, // số chữ số sau dấu phẩy.
                "_chartLabel" => "Thời tiết Hà Nội",
                "propsForDots" => [
                    "r" => 2,
                    "strokeWidth" => 2,
                    "stroke" => "white"
                ]
            ]
        ];
        Cache::put($thoi_tiet_hn_data, $lineMap, 60 * 60 * 2);
        return $lineMap;
    }

    /**
     * @return ResponseApi|object
     */
    public function homeInfo()
    {
        try {
            $listResponseData = [];
            $requestParams = $this->request->all();
            foreach ($requestParams as $key => $value) {
                $type = '';
                $datas = null;
                $homeBlock = new HomeInfo();
                switch ($key) {
                    case self::BLOCK_TYPE_TOPNEW:
                        $datas = $this->paperApi->hit();
                        break;
                    case self::BLOCK_TYPE_TIMELINE:
                        $datas = $this->paperApi->timeLine();
                        break;
                    case self::BLOCK_TYPE_POPULAR:
                        $datas = $this->paperApi->mostPopulator();
                        break;
                    case self::BLOCK_TYPE_FORMARD:
                        $datas = $this->paperApi->forward();
                        break;
                    case self::BLOCK_TYPE_TOPSEARCH:
                        $datas = $this->paperApi->tags();
                        break;
                    case self::BLOCK_TYPE_IMAGES:
                        $datas = $this->paperApi->listImages();
                        break;
                    case self::BLOCK_TYPE_RANDOM:
                    case self::BLOCK_TYPE_DEFAULT:
                        $datas = $this->paperApi->getRandomPapers(6);
                        break;
                    case self::BLOCK_TYPE_PRO:
                        $datas = $this->paperApi->mostRecents();
                        break;
                    case self::BLOCK_TYPE_LISTWRITER:
                        $datas = $this->writerApi->allWriter(8);
                        break;
                    case self::BLOCK_TYPE_CHART:
                        $datas = $this->chart();
                        break;
                    case self::BLOCK_TYPE_VIDEO:
                        $datas = $this->paperApi->getLastVideo();
                        break;
                    case strpos($key, self::BLOCK_TYPE_CATEGORY) !== false:
                        $custom = new DataObject();
                        $custom->setData('category', $this->categoryApi->getCategoryById($value, false));
                        $custom->setData('items', $this->categoryApi->paperByCategory($value, 6)->getItems());
                        $datas = $custom;
                        break;
                    default:
                        $datas = null;
                        break;
                }
                $homeBlock->setType($key);
                $homeBlock->setDatas($datas);
                $listResponseData[] = $homeBlock;
            }
            return $this->responseApi->setResponse($this->responseData->setResponse($listResponseData));
        } catch (\Throwable $th) {
            return $this->responseApi->setResponse($this->responseData->setMessage($th->getMessage()))->setStatusCode(500);
        }
    }

    /**
     * @return ResponseApi
     */
    public function tags()
    {
        $tagList = new TagList();
        $ids = DB::table(PaperTagInterface::TABLE_NAME)->select(['base_value as base', 'id'])->groupBy('base')->pluck('id')->toArray();
        $tags = PaperTag::select([
            PaperTagInterface::ATTR_BASE_VALUE,
            PaperTagInterface::ATTR_VALUE,
            "id",
            PaperTagInterface::ATTR_TYPE
        ])->whereIn('id', $ids)->orderByDesc("id")->paginate($this->request->get("limit", 12));
        $tagList->setItems($this->convertTag->convertListData($tags));
        $tagList->setPageInfo($this->convertTag->convertPageInfo($tags));
        return $this->responseApi->setResponse($this->responseData->setResponse($tagList));
    }

    function eventDay(string $date)
    {
        /**
         * @var \Illuminate\Http\Client\Response $response
         */
        $response = Http::post("https://lichngaytot.com/ajax/NgayNayNamXuaAjax", ["ngayxem" => $date]);
        $dom = $this->loadDom($response->body());
        $tr = $dom->getElementsByTagName("tr");
        $arr = [];
        $parentKey = '';
        foreach ($tr as $e) {
            $count = $e->childNodes->count();
            foreach ($e->childNodes as $td) {
                $text  = ($td->textContent);
                if (empty(str_replace(" ", "", str_replace(["\n", "\r"], "", $text)))) {
                    continue;
                }
                if ($count == 3) {
                    $parentKey = $text;
                    // $arr[$parentKey][] = $text;
                } else {
                    if (!strpos($text, ":")) {
                        continue;
                    }
                    $values = explode(": ", $text, 2);
                    $arr[$parentKey][] = [
                        "date" => $values[0],
                        "title" => $values[1]
                    ];
                }
            }
        }
        return  $this->responseApi->setResponse($this->responseData->setResponse($arr));;
    }
}
