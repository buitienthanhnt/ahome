<?php

namespace App\Listeners;

use App\Api\CategoryApi;
use App\Api\ManagerApi;
use App\Api\PaperApi;
use App\Api\WriterApi;
use App\Http\Controllers\Api\ManagerApiInterface;
use Illuminate\Http\Request;
use Thanhnt\Nan\Helper\LogTha;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CacheApiListen
{
    protected $logTha;
    protected $request;

    protected $managerApi;
    protected $writerApi;
    protected $categoryApi;
    protected $paperApi;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        Request $request,
        LogTha $logTha,
        ManagerApi $managerApi,
        WriterApi $writerApi,
        CategoryApi $categoryApi,
        PaperApi $paperApi
    )
    {
        $this->request = $request;
        $this->logTha = $logTha;
        $this->managerApi = $managerApi;
        $this->writerApi = $writerApi;
        $this->categoryApi = $categoryApi;
        $this->paperApi = $paperApi;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        try {
            /**
             * cache for homeInfo api
             */
            $this->request->headers->set("forget_cache", true);
            $this->request->merge([
                "TopNew" => 1,
                "Popular" => 1,
                "TopSearch" => 1,
                "Forward" => 1,
                "TimeLine" => 1,
                "Pro" => 1,
                "Video" => 1,
                "Images" => 1,
                "Chart" => 1,
                "ListWriter" => 1,
                "Random" => 1,
                "SearchAll" => 1,
                "Default" => 1
            ]);
            $homeInfo = $this->managerApi->getHomeInfo();

            /**
             * cache for paper by writer api
             */
            $listWriter = array_filter($homeInfo, function ($block){
                return $block->getType() === ManagerApiInterface::BLOCK_TYPE_LISTWRITER;
            });
            foreach (current($listWriter)->getDatas() as $writer) {
                /**
                 * cache for paper of writer.
                 */
                $this->writerApi->getPapers($writer->getId());
            }

            /**
             * cache for category
             */
            $topCategory = $this->categoryApi->getCategoryTop();
            foreach ($topCategory as $categoryItem){
                /**
                 * cache for paper category top
                 */
                $this->categoryApi->paperByCategory($categoryItem->getId(), 12, true);
            }
            /**
             * cache for category tree
             */
            $this->categoryApi->getCategoryTree();

            /**
             * cache for paper list
             */
            $this->paperApi->listPapers();
        } catch (\Exception $exception) {
        }
    }
}
