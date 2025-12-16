<?php

namespace App\Api;

use App\Api\Data\DataObject;
use App\Api\Data\Other\HomeInfo;
use App\Helper\HelperFunction;
use App\Http\Controllers\Api\ManagerApiInterface;
use Illuminate\Http\Request;

class ManagerApi extends BaseApi
{
    protected $request;
    protected $paperApi;
    protected $categoryApi;
    protected $writerApi;

    protected $helperFunction;

    function __construct(
        Request $request,
        PaperApi $paperApi,
        CategoryApi $categoryApi,
        WriterApi $writerApi,
        HelperFunction $helperFunction
    ) {
        $this->request = $request;
        $this->paperApi = $paperApi;
        $this->categoryApi = $categoryApi;
        $this->writerApi = $writerApi;
        $this->helperFunction = $helperFunction;
    }

    protected function chart()
    {
        $lineMap = [
            "data" => [
                "labels" => ["Jan", "Feb", "March", "April", "May", "June", 'nan'],
                "datasets" => [
                    [
                        "data" => [
                            random_int(1, 100),
                            random_int(1, 100),
                            random_int(1, 100),
                            random_int(1, 100),
                            random_int(1, 100),
                            random_int(1, 100),
                            random_int(1, 100)
                        ]
                    ]
                ]
            ],
            "yAxisLabel" => "$",
            "yAxisSuffix" => "đ",
            "bezier" => true,
            "yAxisInterval" => 1,
            "chartConfig" => [
                "backgroundColor" => "#e26a00",
                "backgroundGradientFrom" => "#ff7cc0", // 82baff
                "backgroundGradientTo" => "#82baff",   // ffa726
                "decimalPlaces" => 1, // số chữ số sau dấu phẩy.
            ]
        ];
        return $lineMap;
    }

    function getHomeInfo()
    {
        try {
            $listResponseData = [];
            $requestParams = $this->request->all();
            foreach ($requestParams as $key => $value) {
                $datas = null;
                $homeBlock = new HomeInfo();
                switch ($key) {
                    case ManagerApiInterface::BLOCK_TYPE_TOPNEW:
                        $datas = $this->paperApi->hit();
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_TIMELINE:
                        $datas = $this->paperApi->timeLine();
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_POPULAR:
                        $datas = $this->paperApi->mostPopulator();
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_FORMARD:
                        $datas = $this->paperApi->forward();
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_TOPSEARCH:
                        $datas = $this->paperApi->tags();
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_IMAGES:
                        $datas = $this->paperApi->listImages();
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_RANDOM:
                        $datas = $this->paperApi->getRandomPapers(6);
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_PRO:
                        $datas = $this->paperApi->mostRecents();
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_LISTWRITER:
                        $datas = $this->writerApi->allWriter(8);
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_CHART:
                        $datas = $this->chart();
                        break;
                    case ManagerApiInterface::BLOCK_TYPE_VIDEO:
                        $datas = $this->paperApi->getLastVideo(true);
                        break;
                    case strpos($key, ManagerApiInterface::BLOCK_TYPE_CATEGORY) !== false:
                        $custom = new DataObject();
                        $custom->setData('category', $this->categoryApi->getCategoryById($value, false));
                        $custom->setData('items', $this->categoryApi->paperByCategory($value, 6)->getItems());
                        $datas = $custom;
                        break;
                    default:
                        $datas = null;
                        break;
                }
                if (!$datas) {
                    continue;
                }
                $homeBlock->setType($key);
                $homeBlock->setDatas($datas);
                $listResponseData[] = $homeBlock;
            }
            return $listResponseData;
        } catch (\Throwable $th) {
            return [];
        }
    }
}
