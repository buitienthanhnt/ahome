<?php

namespace App\Api;

use App\Api\Convert\ConvertPaper;
use App\Api\Data\Paper\PaperItem;
use App\Helper\HelperFunction;
use App\Jobs\UpPaperFireBase;
use App\Models\Comment;
use App\Models\Paper;
use App\Models\PaperContent;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Thanhnt\Nan\Helper\LogTha;

class PaperFirebaseApi extends BaseApi
{
    protected $helperFunction;
    protected $request;
    protected $writerApi;
    protected $paperApi;
    protected $convertPaper;
    /**
     * @var PaperContent $paperContent
     */
    protected $paperContent;

    protected $paperRepository;
    protected $managerApi;

    function __construct(
        FirebaseService $firebaseService,
        HelperFunction $helperFunction,
        Request $request,
        LogTha $logTha,
        PaperContent $paperContent,
        PaperRepository $paperRepository,
        ConvertPaper $convertPaper,
        ManagerApi $managerApi,
        PaperApi $paperApi,
        WriterApi $writerApi
    )
    {
        $this->helperFunction = $helperFunction;
        $this->request = $request;
        $this->paperContent = $paperContent;
        $this->paperRepository = $paperRepository;
        $this->convertPaper = $convertPaper;
        $this->managerApi = $managerApi;
        $this->paperApi = $paperApi;
        $this->writerApi = $writerApi;
        parent::__construct($firebaseService, $logTha);
    }

    /**
     * @param int $paperId
     * @return Paper| null
     */
    public function getDetail(int $paperId)
    {
        $paperDetail = null;
        $paperKey = 'paperDetail_' . $paperId;
        if (Cache::has($paperKey)) {
            $paperDetail = Cache::get($paperKey);
        } else {
            $paperDetail = Paper::find($paperId);
            if ($paperDetail) {
                Cache::put($paperKey, $paperDetail);
            }
        }
        return $paperDetail;
    }

    /**
     * @param int $paperId
     * @return Comment| null
     */
    public function getComment(int $comment_id)
    {
        return Comment::find($comment_id);
    }

    function paperInFirebase(): array
    {
        $userRef = $this->firebaseDatabase->getReference('/newpaper/papers');
        $snapshot = $userRef->getSnapshot();
        $values = $snapshot->getValue() ?: [];
        if ($values) {
            foreach ($values as $key => &$val) {
                $val = $this->formatPaperFirebase($val);
            }
        }
        return $values;
    }

    function paperInHome(): array
    {
        $userRef = $this->firebaseDatabase->getReference('/newpaper/home');
        $snapshot = $userRef->getSnapshot();
        $values = $snapshot->getValue() ?: [];
        if ($values) {
            foreach ($values as $key => &$val) {
                $val = $this->formatPaperFirebase($val);
            }
        }
        return $values;
    }

    public function formatPaperFirebase($paperData)
    {
        if (isset($paperData['id'])) {
            return $paperData;
        }
        return array_values($paperData)[0];
    }

    public function addFirebase($paperId, $hidden = []): array
    {
        /**
         * @var Paper $_paper
         */
        $_paper = Paper::find($paperId);

        /**
         * tham chieu ref
         */
        $userRef = $this->firebaseDatabase->getReference("/newpaper/{$this->request->get('type', 'papers')}/" . $_paper->id);
        if ($userRef->getSnapshot()->getValue()) {
            return [
                'status' => false,
                'value' => null
            ];
        }

        /**
         * get paper item data.
         */
        $paperData = $this->convertPaper->convertItemData($_paper)->toArray();
        /**
         * upload to papers firebase realtimeDatabase
         */
        $userRef->push($paperData);

        /**
         * upload paperImage to storage
         */
        $firebaseImage = $paperData['image'];
        if (!empty($_paper)) {
            if (isset($paperData['image']) && !empty($paperData['image'])) {
                /**
                 * upload image of paper to fireStorage
                 * chay khi dung local trong truong hop data truoc do dung image server.
                 */
                if (false) {
                    $firebaseImage = $this->upLoadImageFirebase($paperData['image'], $this->request->get('type', null));
                    if ($firebaseImage) {
                        $paperData['image'] = $firebaseImage;
                    } else {
                        unset($paperData['image']);
                    }
                }
            }

            /**
             * queue for async upload data of paper to firebase
             * paper_id & paper image path firebase.
             */
            if (!$this->request->get('type', null)) {
                dispatch(new UpPaperFireBase($paperId, $firebaseImage ?? null));
            }

            /**
             * ghi log
             */
            $this->logTha->logFirebase('info', " -> Added for paperId: " . $paperId . " to paperList firebase");

            if (!$this->request->get('type', null)) {
                Cache::put('paper_in_firebase', $this->paperInFirebase());
            } else {
                Cache::put('paper_in_home', $this->paperInHome());
            }
            /**
             * return.
             */
            $snapshot = $userRef->getSnapshot();
            return [
                'status' => true,
                'value' => $this->formatPaperFirebase($snapshot->getValue())['id']
            ];
        }
        return [
            'status' => false,
            'value' => null
        ];
    }

    /**
     * remove paper in list of realtime database.
     * @param int|string $idInFirebase
     * @return array
     */
    public function removePaperInList($idInFirebase)
    {
        try {
            $userRef = $this->firebaseDatabase->getReference('/newpaper/papers/' . $idInFirebase);
            $paperData = $this->formatPaperFirebase($userRef->getSnapshot()->getValue());
            $userRef->remove();
            $this->logTha->logFirebase('info', 'removed paper data in papers firebase database', [
                'paper' => $idInFirebase
            ]);
            return $paperData;
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'can not remove paper in papers lists firebase: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
        return null;
    }

    function removePaperCategory($paperData): void
    {
        if ($categories = $paperData['categories']) {
            foreach ($categories as $value) {
                try {
                    $userRef = $this->firebaseDatabase->getReference("/newpaper/papersCategory/$value/{$paperData['id']}");
                    $userRef->remove();
                    $this->logTha->logFirebase('info', 'removed paper data in categories firebase database', [
                        'paper' => $paperData['id'],
                        'category' => $value
                    ]);
                } catch (\Throwable $th) {
                    $this->logTha->logFirebase('warning', 'can not remove paper in categories firebase: ' . $th->getMessage(), ['line' => $th->getLine()]);
                }
            }
        }
    }

    function removePaperComment($idInFirebase)
    {
        try {
            $userRef = $this->firebaseDatabase->getReference("/newpaper/comments/$idInFirebase");
            $userRef->remove();
            $this->logTha->logFirebase('info', 'removed paper comments in firebase database', [
                'paper' => $idInFirebase,
            ]);
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'can not remove paper comments: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
    }

    function removePaperInfo($idInFirebase): void
    {
        try {
            $observer = $this->fireStore->collection('detailInfo')->document($idInFirebase);
            $observer->delete();
            $this->logTha->logFirebase('info', 'removed paper info in firestore', [
                'paper' => $idInFirebase,
            ]);
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'can not remove paper info in firestore: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
    }

    function removePaperWriter($paperData)
    {
        try {
            $observer = $this->firebaseDatabase->getReference("/newpaper/writers/{$paperData['writer']}/{$paperData['id']}");
            $observer->remove();
            $this->logTha->logFirebase('info', 'removed paper info in writer', [
                'paper' => $paperData['id'],
                'writer' => $paperData['writer']
            ]);
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'can not remove paper in writers: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
    }

    /**
     * remove all value of paper in firebase
     * @param int|string $idInFirebase
     * @return array
     */
    function removeInFirebase($idInFirebase)
    {
        $this->logTha->logFirebase('info', '<<------- start remove paper in firebase');
        $paperData = $this->removePaperInList($idInFirebase);
        if (empty($paperData)) {
            return [
                'status' => false,
                'value' => null
            ];
        }
        $this->removePaperCategory($paperData);
        $this->rmContentFireStore($idInFirebase);
        $this->removePaperComment($idInFirebase);
        $this->removePaperInfo($idInFirebase);
        $this->removePaperWriter($paperData);
        if (isset($paperData['image_path'])) {
            /**
             * remove image papaper in storage firebase.
             */
            $this->removeImageFirebase($paperData['image_path']);
        }
        $this->logTha->logFirebase('info', 'end remove paper in firebase ------->>');
        $this->updatePaperCache();
        return [
            'status' => true,
            'value' => $idInFirebase
        ];
    }

    function updatePaperCache()
    {
        $userRef = $this->firebaseDatabase->getReference('/newpaper/papers');
        $snapshot = $userRef->getSnapshot();
        Cache::put('paper_in_firebase', $this->paperInFirebase());
    }

    function upSliderImages(array $sliderImages)
    {
        try {
            foreach ($sliderImages as &$value) {
                $value->value = $this->upLoadImageFirebase($value->value);
            }
            return $sliderImages;
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'upSliderImages error: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
    }

    /**
     * @param int|Paper $paper
     * @return void
     */
    function upContentFireStore(Paper $paper)
    {
        // $fireStore = $this->fireStore->collection('newpaper')->document('detailcontent')->snapshot()->data();
        // $this->fireStore->collection('newpaper')->document('detailcontent')->set([
        // 	'12' => '2312312312'
        // ]);
        // $this->fireStore->collection('detailContent')->newDocument()->create([
        // 	'121' => '2312312312'
        // ]);

        // document
        try {
            if (is_numeric($paper)) {
                $paper = $this->getDetail($paper);
            }

            /**
             * get Paper detail
             */
            $paperDetail = $this->convertPaper->convertPaperDetailApi($paper)->toArray();

            /**
             * upload to fireStore
             * dang bi loi nen dung qua realtime database.
             */
            if (false) {
                $this->fireStore->collection('detailContent')->document($paperDetail['id'])->create($paperDetail);
            }
            /**
             * upload to papers firebase realtimeDatabase
             */
            $userRef = $this->firebaseDatabase->getReference("/newpaper/detail/" . $paper->id);
            $userRef->push($paperDetail);
            /**
             *
             * ghi log
             */
            $this->logTha->logFirebase('info', "added for paper detail: " . $paper->id . " to document paper detail firebase", [
                'paper_id' => $paper->id
            ]);

            /**
             * upload slug
             */
//            if ($suggest = $paperDetail['suggest']){
//                foreach ($suggest as $sug) {
//                    $this->addFirebase($sug["id"]);
//                }
//            }
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'add paper detail to firestore error: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
    }

    /**
     * @param int|Paper $paper
     * @return void
     */
    function upPaperInfo(Paper $paper)
    {
        try {
            if (is_numeric($paper)) {
                $paper = $this->getDetail($paper);
            }
            if (empty($paper)) {
                return;
            }
            $observer = $this->fireStore->collection('detailInfo')->document($paper->id);
            if (!$observer->snapshot()->data()) {
                $observer->create($paper->paperInfo());
            } else {
                $observer->delete();
                $observer->create($paper->paperInfo());
            }
            $this->logTha->logFirebase('info', "added for detail info: " . $paper->id . " to document paper info firebase", ['paper' => $paper->id]);
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'add paper info firebase error: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
    }

    /**
     * @param int|Paper   $paper
     * @param string|null $image_path
     * @return void
     */
    function upPaperWriter($paper, $image_path = null): void
    {
        try {
            if (is_numeric($paper)) {
                $paper = $this->getDetail($paper);
            }
            /**
             * get paper item data
             */
            $paperData = $this->convertPaper->convertItemData($paper)->toArray();
            $paperData['image'] = $image_path;

            $writer = $paper->joinWriter;
            /**
             * upload paper to writer
             */
            $userRef = $this->firebaseDatabase->getReference('/newpaper/writers/' . $writer->id . "/" . $paperData['id']);
            $userRef->push($paperData);
            /**
             * ghi log
             */
            $this->logTha->logFirebase('info', "added for paper to writer: " . $paper->id . " to realTime database writer firebase", [
                'paper_id' => $paper->id,
                'writer' => $writer->id
            ]);
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'add paper to writer firebase realtime error: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
    }

    function rmContentFireStore($paperId)
    {
        $this->fireStore->collection('detailContent')->document($paperId)->delete();
    }

    /**
     * @param int|Paper   $paper
     * @param string|null $firebaseImage
     * @return void
     */
    public function addPapersCategory(Paper $paper, $firebaseImage = null)
    {
        $paperData = $this->convertPaper->convertItemData($paper)->toArray();
        if ($firebaseImage) {
            $paperData['image'] = $firebaseImage;
        } else {
            unset($paperData['image']);
        }

        $categories = $paper->listIdCategories();
        foreach ($paper->getTimeline() as $item) {
            /**
             * @var PaperContent $item
             */
            $categories[] = (int)$item->{$item::ATTR_DEPEND_VALUE};
        }

        foreach ($categories as $value) {
            try {
                $userRef = $this->firebaseDatabase->getReference('/newpaper/papersCategory/' . $value . "/{$paperData['id']}");
                $userRef->push($paperData);
                $this->logTha->logFirebase('info', "added for paperId: " . $paperData['id'] . " to paperCategory firebase", [
                    'paperId' => $paperData['id'],
                    'categoryId' => $value
                ]);
            } catch (\Throwable $th) {
                $this->logTha->logFirebase('warning', 'add paper category firebase error: ' . $th->getMessage(), ['line' => $th->getLine()]);
            }
        }
    }

    function upFirebaseTags(Paper $paper){
        $tags = $paper->getTags();
        $paperData = $this->convertPaper->convertItemData($paper)->toArray();
        foreach ($tags as $tag) {
            try {
                $userRef = $this->firebaseDatabase->getReference('/newpaper/papersTag/' . $tag["value"] . "/".$paper->id);
                $userRef->push($paperData);
            } catch (\Throwable $th) {
                $this->logTha->logFirebase('warning', 'add paper tags firebase error: ' . $th->getMessage(), ['line' => $th->getLine()]);
            }
        }
    }

    /**
     * @param int|Paper $paperId
     * @return void
     */
    function upFirebaseComments($paper)
    {
        if (is_numeric($paper)) {
            $paper = $this->getDetail($paper);
        }
        if (empty($paper)) {
            return;
        }
        $commentTree = $paper->getCommentTree(null, 0, 0);
        if (!empty($commentTree) && count($commentTree)) {
            try {
                $userRef = $this->firebaseDatabase->getReference('/newpaper/comments/' . $paper->id)->remove();
                $userRef->push($commentTree);
                $this->logTha->logFirebase('info', "added for comment to list comment firebase", [
                    'paper_id' => $paper->id,
                ]);
            } catch (\Throwable $th) {
                $this->logTha->logFirebase('warning', 'up paper comment to comment list firebase error: ' . $th->getMessage(), ['line' => $th->getLine()]);
            }
        }
    }

    function pullFirebaseComment()
    {
        $observer = $this->firebaseDatabase->getReference('/newpaper/addComments/');
        $snapshot = $observer->getSnapshot()->getValue();
        if (!empty($snapshot)) {
            Comment::insert($snapshot);
            $observer->remove();
            if ($paperIds = array_unique(array_column($snapshot, 'paper_id'))) {
                foreach ($paperIds as $id) {
                    $this->upFirebaseComments($id);
                }
            }
        }
    }

    function pullFirebasePaperLike()
    {
        $observer = $this->firebaseDatabase->getReference('/newpaper/addLike/');
        $snapshot = $observer->getSnapshot()->getValue();
        if (empty($snapshot)) {
            return;
        }
        foreach ($snapshot as $value) {
            if ($paper = $this->getDetail($value['paper_id'])) {
                $viewSource = $paper->viewSource();
                $viewSource->like = $viewSource->like + ($value['type'] == 'like' ? ($value['action'] == 'add' ? 1 : 0) : 0);
                $viewSource->heart = $viewSource->heart + ($value['type'] == 'heart' ? ($value['action'] == 'add' ? 1 : 0) : 0);
                $viewSource->save();
            }
        }
        $observer->remove();

        if ($paperIds = array_unique(array_column($snapshot, 'paper_id'))) {
            foreach ($paperIds as $id) {
                $paper = $this->getDetail($id);
                $this->upPaperInfo($paper);
            }
        }
    }

    function pullFirebaseComLike()
    {
        $observer = $this->firebaseDatabase->getReference('/newpaper/addCommentLike/');
        $snapshot = $observer->getSnapshot()->getValue();
        $paperIds = [];
        if (empty($snapshot)) {
            return;
        }
        foreach ($snapshot as $value) {
            if ($comment = $this->getComment($value['comment_id'])) {
                $comment->like = $comment->like + ($value['type'] == 'like' ? 1 : -1);
                $paperIds[] = $comment->paper_id;
                $comment->save();
            }
        }
        $observer->remove();

        if (count(array_unique($paperIds))) {
            foreach ($paperIds as $id) {
                $paper = $this->getDetail($id);
                $this->upFirebaseComments($paper);
            }
        }
    }

    function upFirebaseHomeInfo(): bool
    {
        try {
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
            foreach ($homeInfo as $value) {
                $uploadData[] = $value->toArray();
            }
            $userRef = $this->firebaseDatabase->getReference('/newpaper/homeInfo');
            $snapshot = $userRef->getSnapshot();
            /**
             * check infoHome data in firebase
             *  if exist remove.
             */
            if ($snapshot->getValue()) {
                $userRef->remove();
            }
            $userRef->push($uploadData);
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return false;
    }

    /**
     * upload writers for firebase
     * @throws \Kreait\Firebase\Exception\DatabaseException
     */
    function upFirebaseWriter()
    {
        $uploadData = [];
        foreach ($this->writerApi->allWriter() as $writer) {
            $uploadData[] = $writer->toArray();
        }
        $userRef = $this->firebaseDatabase->getReference('/newpaper/listWriters');
        $snapshot = $userRef->getSnapshot();
        /**
         * check writer data in firebase
         *  if exist remove.
         */
        if ($snapshot->getValue()) {
            $userRef->remove();
        }
        $userRef->push($uploadData);
        return;
    }

    // https://firebase-php.readthedocs.io/en/7.15.0/cloud-messaging.html
    function sendNotification(int $paperId = 1)
    {
        $paper = $this->paperRepository->getById($paperId);
        if (empty($paper)) {
            return false;
        }
        /**
         * @var PaperItem $paperData
         */
        $paperData = $this->convertPaper->convertItemData($paper);
        $messaging = $this->messagesing;
        $notification = Notification::fromArray([
            'title' => $paperData->getTitle(),
            'body' => '',
            'image' => $paperData->getImage(),
        ]);

        $topic = 'NotifiAuto';
        // gửi tin nhắn theo topic thì trên mobile cần phải đăng ký topic trước.
        // NotifiAuto: là topic mặc định cho tất cả các loại thông báo.
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notification)
            ->withData([
                'screen' => "PaperDetail/" . $paperData->getId(),
                'paper' => json_encode($paperData->toArray())
            ]);
        $messaging->send($message);
        return true;
    }

    function testFirestore(){
        try {
//            $factory = new Factory();
//            $defaultDatabase = $factory->withProjectId("newpaper-25148")
//                ->createFirestore()
//                ->database()->collection("detailcontent");
//            dd($defaultDatabase);

//            $document = $firestore->document('newpaper/detailcontent');
            $data = $this->fireStore->documents(["abc/bbb"]);
//            $value = $data->snapshot();
            dd($data);
        }catch (\Exception $exception){
            dd($exception);
        }
    }
}
