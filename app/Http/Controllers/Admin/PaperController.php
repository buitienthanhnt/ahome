<?php

namespace App\Http\Controllers\Admin;

use App\Api\PaperFirebaseApi;
use App\Enums\CacheStorage;
use App\Helper\HelperFunction;
use App\Helper\Page;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateHomeApi;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Models\PaperContentInterface;
use App\Models\PaperInterface;
use App\Models\RemoteSourceHistory;
use App\Models\RemoteSourceHistoryInterface;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Thanhnt\Nan\Helper\LogTha;
use Thanhnt\Nan\Helper\RemoteSourceManager;
use Thanhnt\Nan\Helper\StringHelper;
use Exception;
use Illuminate\Support\Facades\Cache;
use Throwable;

class PaperController extends Controller implements PaperControllerInterface
{
    use Page;
    use StringHelper;

    /**
     * @var CartService $cartService
     */
    protected $cartService;
    protected $request;
    protected $paper;
    protected $category;
    protected $notification;
    protected $helperFunction;
    protected $logTha;
    protected $remoteSourceManager;
    protected $paperFirebaseApi;

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category,
        Notification $notification,
        HelperFunction $helperFunction,
        LogTha $logTha,
        CartService $cartService,
        RemoteSourceManager $remoteSourceManager,
        PaperFirebaseApi $paperFirebaseApi
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->logTha = $logTha;
        $this->category = $category;
        $this->notification = $notification;
        $this->helperFunction = $helperFunction;
        $this->cartService = $cartService;
        $this->remoteSourceManager = $remoteSourceManager;
        $this->paperFirebaseApi = $paperFirebaseApi;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function listPaper()
    {
        $papers = $this->paper->orderBy("updated_at", "DESC")->paginate(8);
        return view("adminhtml.templates.papers.list", compact("papers"));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function createPaper()
    {
        return view("adminhtml.templates.papers.create");
    }

    /**
     * @param int $paper_id
     * @return array{type: string, key: int, value: string, paper_id: int}
     */
    protected function convertRequestData(int $paper_id): array
    {
        $datas = $this->request->toArray();
        $returnValues = [];
        $storagePath = getStoragePath();
        /**
         * format page content data
         */
        foreach ($datas as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $val = null;
            $now = now()->toDateTimeString();
            if (strpos($key, 'images_imagex') !== false) {
                $img_desc = $datas[str_replace('images_imagex_', 'imagex_desc_', $key)] ?: null;
                $returnValues[] = [
                    "type" => PaperContentInterface::TYPE_IMAGE,
                    "key" => $key,
                    "value" => str_starts_with($value, $storagePath) ? str_replace($storagePath, '', $value) : $value,
                    "paper_id" => $paper_id,
                    "depend_value" => $img_desc,
                    "created_at" => $now,
                    "updated_at" => $now,
                ];
                continue;
            }

            switch ($key) {
                case PaperContentInterface::TYPE_PRICE:
                    $val = [
                        "type" => PaperContentInterface::TYPE_PRICE,
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case PaperContentInterface::TYPE_SLIDER:
                    $val = [
                        "type" => PaperContentInterface::TYPE_SLIDER,
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case PaperContentInterface::TYPE_CONTENT:
                    $val = [
                        "type" => PaperContentInterface::TYPE_CONTENT,
                        "key" => $key,
                        "value" => $value,
                        "paper_id" => $paper_id,
                        "depend_value" => null,
                    ];
                    break;
                case PaperContentInterface::TYPE_TIMELINE_DEPEND:
                    break;
                case PaperContentInterface::TYPE_TIMELINE:
                    $val = [
                        "type" => PaperContentInterface::TYPE_TIMELINE,
                        "key" => $key,
                        "depend_value" => $datas[PaperContentInterface::TYPE_TIMELINE_DEPEND],
                        "value" => $value,
                        "paper_id" => $paper_id
                    ];
                    break;
                case  PaperContentInterface::TYPE_VIDEO:
                    $val = [
                        "type" => PaperContentInterface::TYPE_VIDEO,
                        "key" => $key,
                        "depend_value" => $datas[PaperContentInterface::TYPE_VIDEO_DEPEND],
                        "value" => $value,
                        "paper_id" => $paper_id
                    ];
                    break;
                default:
            }
            if (empty($val)) {
                continue;
            }
            $val["created_at"] = $now;
            $val["updated_at"] = $now;
            $returnValues[] = $val;
        }
        return $returnValues;
    }

    protected function insertPaperContent($new_id)
    {
        $contents = $this->convertRequestData($new_id);
        PaperContent::insert($contents);
    }

    /**
     * @return Paper
     */
    protected function saveMainPaper()
    {
        $paper = $this->paper;
        /**
         * upload image of paper to fireStorage
         */
        $image_path = $this->request->get(PaperInterface::ATTR_IMAGE_PATH, "");
        $storage_path = getStoragePath();
        // $image_path = $this->paperFirebaseApi->upLoadImageFirebase($this->request->get(PaperInterface::ATTR_IMAGE_PATH));
        $paper->fill([
            PaperInterface::ATTR_TITLE => $this->request->get(PaperInterface::ATTR_TITLE),
            PaperInterface::ATTR_URL_ALIAS => $this->formatPath($this->request->get(PaperInterface::ATTR_URL_ALIAS) ?: $this->request->get(PaperInterface::ATTR_TITLE)),
            PaperInterface::ATTR_SHORT_CONTENT => $this->request->get(PaperInterface::ATTR_SHORT_CONTENT),
            PaperInterface::ATTR_IMAGE_PATH => str_starts_with($image_path, $storage_path) ? str_replace($storage_path, '', $image_path) : $image_path,
            PaperInterface::ATTR_ACTIVE => $this->request->get(PaperInterface::ATTR_ACTIVE) ? true : false,
            PaperInterface::ATTR_SHOW => $this->request->boolean(PaperInterface::ATTR_SHOW),
            PaperInterface::ATTR_AUTO_HIDE => $this->request->boolean(PaperInterface::ATTR_AUTO_HIDE, false),
            PaperInterface::ATTR_SHOW_TIME => $this->request->get(PaperInterface::ATTR_SHOW_TIME),
            PaperInterface::ATTR_SHOW_WRITER => $this->request->boolean(PaperInterface::ATTR_SHOW_WRITER),
            PaperInterface::ATTR_WRITER => $this->request->get(PaperInterface::ATTR_WRITER)[0] ?? null,
        ]);
        $paper->save();
        return $paper;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function insertPaper()
    {
        $request = $this->request;
        try {
            $paper = $this->saveMainPaper();
            if ($new_id = $paper->id) {
                /**
                 * save for history
                 */
                if ($request_url = $request->get("source_request")) {
                    /**
                     * save source into database.
                     */
                    $this->saveRemoteSourceHistory($request_url, RemoteSourceHistoryInterface::TYPE_PAPER, $new_id, true);
                    /**
                     * log remote source of paper.
                     */
                    $this->logTha->logRemoteSource("info", urldecode($request_url));
                }
                /**
                 * clear hit_paper cache
                 */
                if ($request->boolean(PaperInterface::ATTR_SHOW)) {
                    Cache::forget(CacheStorage::PAPER_HIT);
                    Cache::forget(CacheStorage::BLOCK_MORNING_TOP_LEFT);
                    Cache::forget(CacheStorage::BLOCK_MORNING_TOP_RIGHT);
                }
                /**
                 * Gửi thông báo cho topic auto.
                 * push notification to mobile
                 */
                // $all_fcm = $this->notification->where("active", true)->get()->toArray();
                // $this->helperFunction->push_notification_json($all_fcm, $paper);
                if ($request->boolean(PaperInterface::ATTR_EX_PUSH_MESSAGE)) {
                    $this->paperFirebaseApi->sendNotification($new_id);
                }
                UpdateHomeApi::dispatch()->delay(8);
            }
            return redirect()->route('admin_list_paper')->with("success", "added success new paper!");
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * @param int $paper_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function editPaper($paper_id)
    {
        /**
         * @var Paper $paper
         */
        $paper = $this->paper->find($paper_id);
        return view("adminhtml.templates.papers.edit", compact("paper"));
    }

    /**
     * @param int $paper_id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function updatePaper($paper_id)
    {
        $paper = $this->paper->find($paper_id);
        $this->paper = $paper;
        if ($paper) {
            try {
                /**
                 * update main content.
                 */
                $this->saveMainPaper();
                return redirect()->back()->with("success", "updated success");
            } catch (Throwable $th) {
                return redirect()->back()->with("error", $th->getMessage());
            }
        }
        return redirect()->back()->with("error", "update error, the paper not found!");
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function deletePaper()
    {
        /**
         * @var Paper $paper
         */
        $paper = $this->paper::find($this->request->get("paper_id"));
        if ($paper) {
            /**
             * delete main paper.
             */
            $paper->delete();
            return response(json_encode([
                "code" => "200",
                "value" => "deleted: success!"
            ]), 200);
        } else {
            return response(json_encode([
                "code" => 400,
                "value" => "the input source not found!"
            ]), 400);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function newByUrl()
    {
        return view("adminhtml.templates.papers.new_by_url");
    }

    /**
     * @param string $source_url
     * @return null|RemoteSourceHistory
     */
    protected function checkSourceExist(string $source_url)
    {
        return (RemoteSourceHistory::where(RemoteSourceHistoryInterface::ATTR_URL_VALUE, $source_url)->first());
    }

    /**
     * create paper by remote source
     */
    public function sourcePaper()
    {
        $source_request = $this->request->get('source_request');
        if ($remoteData = $this->checkSourceExist($source_request)) {
            return redirect()->back()->with("error", "the url source exist with id = " . $remoteData->{RemoteSourceHistory::ATTR_PAPER_ID});
        };
        $sourceData = $this->remoteSourceManager->source($this->request);
        if (!$sourceData) {
            return redirect()->route('admin_new_by_url')->with("error", "can not parse source!");
        } else {
            $sourceData['source_request'] = $this->request->get('source_request');
            return view("adminhtml.templates.papers.create", ["value" => $sourceData]);
        }
    }

    /**
     * @param string $request_url
     * @param string|int $type
     * @param int $paper_id
     * @param bool $active
     * @return void
     */
    protected function saveRemoteSourceHistory(string $request_url, $type = RemoteSourceHistoryInterface::TYPE_PAPER, $paper_id = null, $active = true)
    {
        $history = new RemoteSourceHistory([
            RemoteSourceHistoryInterface::ATTR_URL_VALUE => $request_url,
            RemoteSourceHistoryInterface::ATTR_TYPE => $type,
            RemoteSourceHistoryInterface::ATTR_PAPER_ID => $paper_id,
            RemoteSourceHistoryInterface::ATTR_ACTIVE => $active
        ]);
        $history->save();
    }
}
