<?php

namespace App\Helper;

use App\Models\Paper;
use Exception;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\DomHtml;
use App\Constant\AttributeInterface;
use Illuminate\Support\Facades\Cache;

class HelperFunction
{
    use DomHtml;
    use Nan;

    /**
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getConfigData(string $name)
    {
        try {
            $allConfigCache = 'allConfigCache';
            if ($allConfig = Cache::get($allConfigCache)) {
                return $allConfig[$name] ?? null;
            }
            $allConfig = DB::table($this->coreConfigTable())->select(['name', 'value'])->get()->toArray();
            $newFormat = [];
            foreach ($allConfig as $value) {
                $newFormat[$value->name] = $value->value;
            }
            Cache::put($allConfigCache, $newFormat);
            return $newFormat[$name] ?? null;
        } catch (\Throwable $th) {
            dd($th->getMessage());
            //throw $th;
        }
        return null;
    }

    /**
     * @param string $name
     * @param null   $default
     * @return mixed|null
     */
    public function getConfig(string $name, $default = null)
    {
        try {
            $config_cache = 'config_value_' . $name;
            if (Cache::has($config_cache)) {
                return Cache::get($config_cache);
            }
            $value = $this->getConfigData($name);
            Cache::put($config_cache, $value);
            return $value;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $default;
    }

    /**
     * @param string      $name
     * @param string      $value
     * @param string      $type
     * @param string|null $description
     * @return array
     * @throws Exception
     */
    public function saveConfig(string $name, string $value, string $type = "text", string $description = null): array
    {
        DB::beginTransaction();
        try {
            $saveStatus = DB::table($this->coreConfigTable())->updateOrInsert([AttributeInterface::ATTR_NAME => $name, AttributeInterface::ATTR_VALUE => $value, AttributeInterface::ATTR_DESCRIPTION => $description, AttributeInterface::ATTR_TYPE => $type]);
            if ($saveStatus) {
                $configValue = $this->getConfigData($name);
            } else {
                throw new Exception();
            }
            DB::commit();
            return ["status" => true, "configValue" => $configValue];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return ["status" => false, "value" => null];
    }

    /**
     * @param string      $name
     * @param string      $value
     * @param string      $type
     * @param string|null $description
     * @return array
     * @throws Exception
     */
    function updateConfig(string $name, string $value, string $type = "text", string $description = null)
    {
        DB::beginTransaction();
        try {
            $insert_value = DB::table($this->coreConfigTable())->where(AttributeInterface::ATTR_NAME, $name)->limit(1)->update([AttributeInterface::ATTR_NAME => $name, AttributeInterface::ATTR_VALUE => $value, AttributeInterface::ATTR_DESCRIPTION => $description, AttributeInterface::ATTR_TYPE => $type]);
            DB::commit();
            return ["status" => true, "value" => $insert_value];
        } catch (Exception $e) {
            DB::rollBack();
            return ["status" => false, "value" => null];
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param int $config_id
     * @return array
     */
    function deleteConfig(int $config_id)
    {
        DB::beginTransaction();
        try {
            $config = DB::table($this->coreConfigTable())->find($config_id);
            DB::table($this->coreConfigTable())->delete($config_id);
            DB::commit();
            return [
                'status' => true,
                'configValue' => $config
            ];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
        return ['status' => false, 'configValue' => null];
    }

    // repalce image url for app by use ip address.
    public function replaceImageUrl(string $imageUrl = ""): string
    {
        if (!$imageUrl) {
            return $this->defaultUrl();
        }
        $domain = "";
        $ip = "";
        $main = "";
        try {
            DB::beginTransaction();
            // https://magento23x.jmango360.dev/pub/laravel1/
            if ($target_domain = DB::table($this->coreConfigTable())->where("name", "=", "target_domain")->select()->first()) {
                $target_domain_val = $target_domain->value;
                if ($target_domain_val) {
                    $ex_image_path = (explode('public/storage', $imageUrl));
                    return $target_domain_val . 'public/storage' . $ex_image_path[1];
                }
            }
            $domain = DB::table($this->coreConfigTable())->where(AttributeInterface::ATTR_NAME, "=", "domain")->select()->first()->value;
            $main = DB::table($this->coreConfigTable())->where(AttributeInterface::ATTR_NAME, "=", "main")->select()->first()->value;
            $ip = DB::table($this->coreConfigTable())->where(AttributeInterface::ATTR_NAME, "=", "ip")->select()->first()->value;
        } catch (\Throwable $th) {
            //throw $th;
            return $imageUrl;
        }
        // support for windown platform
        $img = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? str_replace($domain, $ip, $imageUrl) : str_replace($domain, $ip . "/" . $main . "/public", $imageUrl);
        return $img;
    }

    // allway use default image url.
    public function defaultUrl(): string
    {
        try {
            DB::beginTransaction();
            $default_image = DB::table($this->coreConfigTable())->where(AttributeInterface::ATTR_NAME, "=", "default_image")->first('value')->value;
            if ($default_image) {
                return $default_image;
            }
            $is_windown = DB::table($this->coreConfigTable())->where(AttributeInterface::ATTR_NAME, "=", "is_windown")->first('value')->value;
            if (!$is_windown) {
                return public_path('assets/pub_image/defaul.PNG');
            }
            $main = DB::table($this->coreConfigTable())->where(AttributeInterface::ATTR_NAME, "=", "main")->first('value')->value;
            $ip = DB::table($this->coreConfigTable())->where(AttributeInterface::ATTR_NAME, "=", "ip")->first('value')->value;
        } catch (\Throwable $th) {
            return "";
        }
        return "http://" . $ip . "/" . $main . "/public" . "/assets/pub_image/defaul.PNG";
    }

    public static function xml_to_array($xml_string)
    {
        $xml = simplexml_load_string($xml_string, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        return json_decode($json, TRUE);
    }

    function array_to_xml($array)
    {
        return \Spatie\ArrayToXml\ArrayToXml::convert($array, 'root', true, 'UTF-8');
    }

    // post request with request params.
    public function push_notification(array $notification_fcm, Paper $paper): bool
    {
        $curl = curl_init();
        $url = "https://fcm.googleapis.com/fcm/send";

        $authorization = ''; // $authHeaders = array();
        //$authHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
        try {
            $authorization = DB::table($this->coreConfigTable())->where("name", "=", "Authorization")->select()->first()->value;
        } catch (\Throwable $th) {
            return false;
            $th("not has authorization");
        }
        if (empty($authorization)) {
            return false;
        }
        // $authHeaders[] = 'Authorization: ' . $authorization;

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'laravel1',
            CURLOPT_POST => 1,
            CURLOPT_SSL_VERIFYPEER => false, //Bỏ kiểm SSL
            CURLOPT_POSTFIELDS => http_build_query(array(
                'registration_ids' => array_map(fn($notification) => $notification->fcmToken, $notification_fcm),
                'notification' => [
                    "title" => $paper->title,
                    "body" => $paper->short_conten,
                    "image" => $this->replaceImageUrl($paper->image_path),
                ],
                "data" => [
                    "link" => $paper->url_alias,
                    "id" => $paper->id,
                    "screen" => "PaperDetail/$paper->id",
                    "data" => [
                        "id" => $paper->id
                    ]
                ]
            ))
        ));
        $resp = curl_exec($curl);
        var_dump($resp);
        curl_close($curl);

        return $resp;
    }

    /**
     * post request with json params in body
     * @param array $notification_fcm
     * @param Paper $paper
     * @return bool
     */
    public function push_notification_json(array $notification_fcm = [], Paper $paper): bool
    {
        if (!$notification_fcm || !$paper) {
            return false;
        }
        $authorization = ""; //$authHeaders = [];
        try {
            $authorization = DB::table($this->coreConfigTable())->where(AttributeInterface::ATTR_NAME, "=", "Authorization")->select()->first()->value;
        } catch (\Throwable $th) {
            return false;
            $th("not has authorization");
        }

        // $authHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
        // $authHeaders[] = 'Authorization: ' . $authorization;
        $data = array(
            'registration_ids' => array_map(fn($notification) => $notification["fcmToken"], $notification_fcm),
            'notification' => [
                "title" => $paper->title,
                "body" => $paper->short_conten,
                "image" => $this->replaceImageUrl($paper->image_path), // image of notification
                "icon" => "ic_launcher"
            ],
            "data" => [
                "link" => $paper->url_alias,
                "id" => $paper->id,
                "screen" => "PaperDetail/$paper->id",
                "data" => [
                    "id" => $paper->id
                ]
            ]
        );
        $data_string = json_encode($data);
        $url = "https://fcm.googleapis.com/fcm/send"; // $url = "http://laravel1.com/api/testPost";
        // $url = "https://fcm.googleapis.com/v1/projects/react-cli4/messages";  // not run

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(  // || $authHeaders
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: ' . $authorization
            )
        );

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     *
     */
    public function curl_get()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_URL => 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text%3D%22hanoi%2C%20vietnam%22)&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys',
            CURLOPT_USERAGENT => 'Viblo test cURL Request',
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $resp = curl_exec($curl);
        //Dữ liệu thời tiết ở dạng JSON
        $weather = json_decode($resp);
        var_dump($weather);
        curl_close($curl);
    }
}
