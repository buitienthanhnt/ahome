<?php

namespace App\Api;

use App\Helper\ImageUpload;
use App\Services\FirebaseService;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Thanhnt\Nan\Helper\LogTha;
use Kreait\Firebase\Factory;
use Kreait\Firebase\RemoteConfig;

class BaseApi
{
    use ImageUpload;

    const STORERAGE_BUGKET = 'newpaper';
    const DEFAULT_IMAGE = '';

    protected $firebase;
    protected $firebaseDatabase;
    protected $fireStore;
    protected $remoteConfig;
    protected $messagesing;

    protected $logTha;

    public function __construct(
        FirebaseService $firebaseService,
        LogTha $logTha
    ) {
        $this->logTha = $logTha;
        $this->firebase = $firebaseService->firebase;
        $this->firebaseDatabase = $this->firebase->createDatabase();
        $this->fireStore = $firebaseService->fireStore;
        $this->remoteConfig = $firebaseService->remoteConfig;
        $this->messagesing = $firebaseService->messagesing;
    }

    public function upLoadImageFirebase(string $image_link, $folder = null)
    {
        $firebaseFolder = $folder ? $folder . '/' : self::STORERAGE_BUGKET . '/';
        $real_path = $this->url_to_real($image_link);
        if (empty($real_path)) {
            return null;
        }
        $image = fopen($real_path, 'r');
        try {
            $fileType = explode('.', $image_link);
            $fileType = $fileType[count($fileType) - 1];
            /**
             * @var \Kreait\Firebase\Contract\Storage $storage
             */
            $storage = $this->firebase->createStorage();
            $bucket = $storage->getBucket();

            // upload 1 file lên store
            $response = $bucket->upload($image, ['name' => $firebaseFolder . Str::random(10) . '.' . $fileType]);
            $uri = $response->info()['mediaLink'];
            return str_replace(Rest::DEFAULT_API_ENDPOINT . '/download/storage/v1', 'https://firebasestorage.googleapis.com/v0', $uri);
        } catch (\Throwable $th) {
            // echo ($th->getMessage());
        }
        return null;
    }

    /**
     * remove image in storage of firebase.
     * @param string $url_path
     * @return bool
     */
    public function removeImageFirebase(string $url_path)
    {
        try {
            /**
             * @var \Kreait\Firebase\Contract\Storage $storage
             */
            $storage = $this->firebase->createStorage();
            $fileName = self::STORERAGE_BUGKET . "/" . explode("/" . self::STORERAGE_BUGKET . "/", parse_url(urldecode($url_path))['path'], 2)[1];
            $storage->getBucket()->object($fileName)->delete();
            return true;
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'can not remove image paper by: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
        return false;
    }

    /**
     * get default image of firebase remote config(sync with firebase)
     * @return string
     */
    public static function getDefaultImagePath(): string
    {
        if (Cache::has('default_image')) {
            return Cache::get('default_image');
        }
        try {
            $config_path = storage_path("app/" . FirebaseService::CONNECT_FIREBASE_PROJECT . "/firebaseConfig.json");
            $default_image = (new Factory)->withServiceAccount($config_path)->createRemoteConfig()->get()->parameters()['default_image']->toArray()['defaultValue']['value'];
        } catch (\Throwable $th) {
            $default_image = DB::table('core_config')->where('name', 'default_image')->get()->first()->value;
        }
        Cache::put("default_image", $default_image);
        return $default_image;
    }

    /**
     * @param string $key
     * @return \Kreait\Firebase\RemoteConfig\Parameter[]|\Kreait\Firebase\RemoteConfig\Parameter
     */
    public function getRemoteConfig(string $key = '')
    {
        $config_path = storage_path("app/" . FirebaseService::CONNECT_FIREBASE_PROJECT . "/firebaseConfig.json");
        $params = (new Factory)->withServiceAccount($config_path)->createRemoteConfig()->get()->parameters();
        if ($key) {
            return $params[$key];
        }
        return $params;
    }

    public function updateRemoteConfig(string $key, string $value)
    {
        $config_path = storage_path("app/" . FirebaseService::CONNECT_FIREBASE_PROJECT . "/firebaseConfig.json");
        $remoteConfig = (new Factory)->withServiceAccount($config_path)->createRemoteConfig();
        $template = $remoteConfig->get();

        $germanLanguageCondition = RemoteConfig\Condition::named('lang_german')
            ->withExpression("device.language in ['de', 'de_AT', 'de_CH']");

        $germanWelcomeMessage = RemoteConfig\ConditionalValue::basedOn($germanLanguageCondition)->withValue('Willkommen!');

        $welcomeMessageParameter = RemoteConfig\Parameter::named('welcome_message')
            ->withDefaultValue('Welcome!')
            ->withConditionalValue($germanWelcomeMessage);

        $template = $template
            ->withCondition($germanLanguageCondition)
            ->withParameter($welcomeMessageParameter);

        // $remoteConfig->publish($this->getRemoteConfig($key)->withDefaultValue('https://amuaglobal.icu/'));
    }

    public function getWithCache(string $cacheName, $data = null, $time = null)
    {
        if (empty($data)) {
            return $data;
        }
        if (Cache::has($cacheName)) {
            return Cache::get($cacheName, $data);
        }
        Cache::add($cacheName, $data);
        return $data;
    }
}
