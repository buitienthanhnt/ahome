<?php

namespace App\Http\Controllers\Frontend;

use App\Api\PaperApi;
use App\Api\PaperFirebaseApi;
use App\Api\WriterApi;
use App\Enums\CacheStorage;
use App\Events\ViewCount;
use App\Models\CategoryInterface;
use Thanhnt\Nan\Helper\DomHtml;
use App\Models\Category;
use App\Models\PaperTag;
use App\Models\Paper;
use Illuminate\Http\Request;
use App\Helper\HelperFunction;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\ViewBlock\Frontend\LikeMost;
use App\ViewBlock\Frontend\MostPopulator;
use App\ViewBlock\Frontend\Trending;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Thanhnt\Nan\Helper\TokenManager;

class ManagerController extends Controller implements ManagerControllerInterface
{
    use DomHtml;

    protected $request;
    protected $paper;
    /**
     * @var \App\Models\Category $category
     */
    protected $category;
    protected $pageTag;
    protected $helperFunction;
    protected $mostPopulator;
    protected $likeMost;
    protected $trending;
    protected $paperApi;
    protected $writerApi;
    protected $tokenManager;
    protected $user;
    protected $auth;
    protected $paperFirebaseApi;
    protected $session;

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category,
        PaperTag $pageTag,
        HelperFunction $helperFunction,
        MostPopulator $mostPopulator,
        LikeMost $likeMost,
        Trending $trending,
        PaperApi $paperApi,
        WriterApi $writerApi,
        TokenManager $tokenManager,
        User $user,
        Auth $auth,
        PaperFirebaseApi $paperFirebaseApi,
        \Illuminate\Session\Store $session
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
        $this->pageTag = $pageTag;
        $this->helperFunction = $helperFunction;
        $this->mostPopulator = $mostPopulator;
        $this->likeMost = $likeMost;
        $this->trending = $trending;
        $this->paperApi = $paperApi;
        $this->writerApi = $writerApi;
        $this->tokenManager = $tokenManager;
        $this->user = $user;
        $this->auth = $auth;
        $this->paperFirebaseApi = $paperFirebaseApi;
        $this->session = $session;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function homePage()
    {
        $video_contens = null;
        // $video_contens = $this->paper->orderBy("updated_at", "DESC")->take(3)->get();
        // $video_contens = [
        //     ['url' => "https://www.youtube.com/embed/lhYztX6cdg8", "title" => "demo 1"],
        //     ['url' => "https://www.youtube.com/embed/o7o60G-jN54", "title" => "demo 2"],
        //     ['url' => "https://www.youtube.com/embed/EJi1k_Yunco", "title" => "demo 3"],
        //     ['url' => "https://www.youtube.com/embed/9GaIAYhGTE0", "title" => "demo 4"],
        //     ['url' => "https://www.youtube.com/embed/sKdpqk7o5ac", "title" => "demo 5"],
        //     ['url' => "https://www.youtube.com/embed/lovblkkDVDU", "title" => "demo 6"],
        // ];
        return view("frontend/templates/homeContent", compact("video_contens"));
    }

    /**
     * @param string $category_alias
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function categoryView($category_alias)
    {
        /**
         * @var Category $category
         */
        $category = Category::where(CategoryInterface::ATTR_URL_ALIAS, $category_alias)->first();
        $papers = $category->getPaperByCategory(8);
        // ViewCount::dispatch([
        //     "type" => "category",
        //     "id" => $category->id
        // ]);
        return view("frontend/templates/categories", compact("category", "papers"));
    }

    /**
     * @param string $alias
     * @param int $paper_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function paperDetail($alias, $paper_id)
    {
        $key = 'paper_detail_' . $paper_id;
        if (Cache::has($key)){
            $paper = Cache::get($key);
        }else{
            $paper = Cache::remember($key, CacheStorage::TIME_3_H, fn () => $this->paper->find($paper_id));
        }
        if (!in_array($paper_id, $this->session->get('viewed', []))){
            event(new ViewCount([
                "type" => "paper",
                "id" => $paper_id
            ]));
            $this->session->put("viewed", [$paper_id, ...$this->session->get("viewed", [])]);
        }

        if (!$paper){
            return redirect()->route('/');
        }

//        dd($paper->getContents());

        return view("frontend.templates.paper.paper_detail", ['paper' => $paper]);
    }

    /**
     * @param string $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function tagView($tag)
    {
        return view("frontend/templates/tags", ["tag" => $tag]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function search()
    {
        $papers = $this->paperApi->search($this->request->get('search'), false);
        return view('frontend.templates.paper.searchResult', compact('papers'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function loadMore()
    {
        $request = $this->request;
        $type = $request->get("type");
        $page = $request->get("page");
        if ($type) {
            /**
             * @var Category $category
             */
            $category = $this->category->where("url_alias", "like", $type)->first();
            $papers = $category->getPaperPaginate(8, $page);
        }
        $data = view("frontend/templates/paper/component/list_category_paper", ['papers' => $papers])->render();

        return response(json_encode([
            "code" => 200,
            "data" => $data
        ]));
    }

    public function mostPopulator() {
        $mostPopulatorHtml = $this->mostPopulator->toHtml();
        return [
            'code' => 200,
            'dataHtml' => $mostPopulatorHtml
        ];
    }

    public function likeMost(){
        $likeMostHtml = $this->likeMost->toHtml();
        return [
            'code' => 200,
            'dataHtml' => $likeMostHtml
        ];
    }

    public function trendingHtml(){
        $trendingHtml = $this->trending->toHtml();
        return [
            'code' => 200,
            'dataHtml' => $trendingHtml
        ];
    }

    function redirect(){
        if (!$redirect_url = $this->request->get('url')) {
            return redirect()->route("/")->with("error", "your redirect url not found");
        }
        $token = $this->request->get('token');
        $tokenData = (array) $this->tokenManager->getTokenData($token);
        if (empty($tokenData)) {
            return redirect()->route("/")->with("error", "your redirect error expire");
        }
        $tokenData = (array) $tokenData['iss'];
        if (isset($tokenData['id'])) {
            Auth::setUser($this->user->find($tokenData['id']) ?? null);
        }

        if (isset($tokenData['sid'])) {
            if (!Session::isStarted()) {
                Session::start();
            }
            Session::setId($tokenData['sid']);
            Session::start();
        }
        return redirect($redirect_url);
    }

    // =====================================================================

    function formatSug($data)
    {
        return array_chunk(array_map(function ($item) {
            $item['image_path'] = $this->helperFunction->replaceImageUrl($item['image_path']);
            return $item;
        }, $data), 2);
    }

    function redis() {
        $paper = Paper::find(2);
        Cache::store("redis")->put('demo', $paper);
        $cacheValue = Cache::store("redis")->get('demo');
        return $cacheValue;
    }

    // {{url}}/api/upFirebaseComments/122
    // function upFirebaseComments($paper_id, Request $request)
    // {
    //     $paper = $this->paper->find($paper_id);
    //     $this->paperApi->upFirebaseComments($paper);
    //     return [
    //         'success' => true,
    //         'errors' => null
    //     ];
    // }

    // pullFirebaseComment
    // function pullFirebaseComment()
    // {
    //     $this->paperApi->pullFirebaseComment();
    // }

    // // {{url}}/api/pullFirebasePaperLike
    // function pullFirebasePaperLike()
    // {
    //     $this->paperApi->pullFirebasePaperLike();
    // }

    // // {{url}}/api/pullFirebaseComLike
    // function pullFirebaseComLike()
    // {
    //     $this->paperApi->pullFirebaseComLike();
    // }

     function testFirestore()
     {
         $this->paperFirebaseApi->testFirestore();
     }

    function getToken(Request $request): \Illuminate\Http\Response
    {
        if ($request->get('api_key', null) !== $this->tokenManager->get_serect_key()) {
            return response([
                'message' => "api key not found"
            ], 400);
        }

        return response([
            'message' => 'success',
            'token' => $this->tokenManager->getToken([
                'sid' => Session::getId()
            ]),
            'refresh_token' => $this->tokenManager->getRefreshToken([
                'sid' => Session::getId()
            ])
        ]);
    }

    function refreshUserToken(Request $request): \Illuminate\Http\Response
    {
        $refreshToken = $request->get('refresh_token', null);
        if ($refreshToken) {
            $refreshTokenData = $this->tokenManager->getTokenData($refreshToken);
            if (empty($refreshTokenData) || !isset($refreshTokenData['iss'])) {
                return response([
                    'message' => 'refresh token fail!'
                ], 402);
            }

            $dataValue = (array) $refreshTokenData['iss'];
            return response([
                'message' => 'success for refreshToken',
                'token' => $this->tokenManager->getToken(
                    $dataValue
                ),
                'refresh_token' => $this->tokenManager->getRefreshToken(
                    $dataValue
                )
            ], 200);
        }

        return response([
            'message' => 'refresh token fail!'
        ], 402);
    }

    function getTokenData()
    {
        // $time = Carbon::now();
        // dd($time->toTimeString());

        // $date = new DateTime();
        // $timeZone = $date->getTimezone();
        // dd($timeZone);
        // dd($time->timestamp);

        // 1732371368
        // 1732371357066

//        dd($this->tokenManager->getTimeStartTokenData());

        $token = $this->tokenManager->getTokenAuthor();
        if (empty($token)) {
            return response()->json([
                'message' => 'token Authorization is missing. Please set token and try again!'
            ], 403);
        }
        if ($value = $this->tokenManager->getTokenData($token)) {
            $value['iat'] = date("Y-m-d H:i:s", $value['iat']);
            $value['exp'] = date("Y-m-d H:i:s", $value['exp']);
            return ["value" => $value];
        }

        return response()->json([
            'message' => 'token expire. Please refresh token and try again!'
        ], 401);
    }

    function Eager() {
        //  $paper = Paper::find(1)->with('writer');   // Eager load
        //  $paper = Paper::find(1)->joinContent()->get(); // call method tuong duong Paper::find(1)->joinContent call property

        // paginate được gọi từ: Illuminate\Database\Eloquent\Builder
        // $this->helperFunction->getConfigData()
        // $paper = Paper::with('joinWriter')->paginate(8);
        $paper = Paper::with('joinWriter')->find([1,2,3]);
        dd($paper);
    }

    public function getGoogleSignInUrl()
    {
        try {
            $url = Socialite::driver('google')->stateless()
                ->redirect()->getTargetUrl();
            return response()->json([
                'url' => $url,
            ])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function loginCallback(Request $request)
    {
        try {
            $state = $request->input('state');

            parse_str($state, $result);
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->email)->first();
            if ($user) {
                throw new \Exception(__('google sign in email existed'));
            }
            $user = User::create(
                [
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                    'google_id'=> $googleUser->id,
                    'password'=> '123',
                ]
            );
            return response()->json([
                'status' => __('google sign in successful'),
                'data' => $user,
            ], Response::HTTP_CREATED);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => __('google sign in failed'),
                'error' => $exception,
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}
