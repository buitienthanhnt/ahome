<?php

namespace App\Http\Controllers\Admin;

use App\Events\CacheApi;
use App\Events\CacheFrontEvent;
use App\Helper\HelperFunction;
use App\Helper\Nan;
use App\Http\Controllers\Controller;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\LogTha;

class ConfigController extends Controller implements ConfigControllerInterface
{
    use Nan;

    protected $helperFunction;
    protected $logTha;
    protected $request;
    protected $cacheManager;

    function __construct(
        HelperFunction $helperFunction,
        LogTha $logTha,
        Request $request,
        CacheManager $cacheManager
    )
    {
        $this->helperFunction = $helperFunction;
        $this->logTha = $logTha;
        $this->request = $request;
        $this->cacheManager = $cacheManager;
    }

    function listConfig()
    {
        $allOfCoreConfig = null;
        try {
            DB::beginTransaction();
            $allOfCoreConfig = DB::table($this->coreConfigTable())->select()->get();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
        return view("adminhtml.templates.config.list", compact("allOfCoreConfig"));
    }

    function createConfig()
    {
        return view("adminhtml.templates.config.create");
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    function insertConfig()
    {
        $request = $this->request;
        extract($this->helperFunction->saveConfig($request->get("name"), $request->get("value")));
        if (isset($status) && isset($configValue)) {
            $this->logTha->logEvent('info', "added config: ", [
                'key' => $configValue->name,
                'value' => $configValue->value
            ]);
        }
        return redirect()->back()->with("success", "saved config value!");
    }

    function updateConfig()
    {
        $request = $this->request;
        $params = $request->all();
        unset($params['_token']);
        try {
            if (count($params)) {
                foreach ($params as $key => $value) {
                    $this->helperFunction->updateConfig($key, $value);
                    $this->logTha->logEvent('info', "updated config: ", [
                        'key' => $key,
                        'old value' => 'now default null',
                        'new value' => $value
                    ]);
                }
            }
            return redirect()->back()->with("success", "saved config value!");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "save config error!");
        }
    }

    function deleteConfig()
    {
        $request = $this->request;
        $config_id = $request->get('config_id');
        try {
            extract($this->helperFunction->deleteConfig($config_id));
            if (!$configValue) {
                return response(json_encode([
                    "code" => "401",
                    "value" => "deleted: success!"
                ]), 401);
            }
            $this->logTha->logEvent('warning', "deleted config with : {key} & {value}", [
                'key' => $configValue->name,
                'value' => $configValue->value
            ]);
            return response(json_encode([
                "code" => "200",
                "value" => "deleted: success!"
            ]), 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            "code" => "401",
            "value" => "deleted: success!"
        ]), 401);
    }

    function clearCache()
    {
        try {
            /**
             * !refresh is flush all data
             * refresh is keep old data and update for cache data in event(some api cache data)
             */
            if (!$this->request->boolean("is_refresh")) {
                $this->cacheManager->flush();
            } else {
                CacheFrontEvent::dispatch();
                CacheApi::dispatch();
            }
            return response()->json([
                'code' => 200,
                'value' => 'cache cleared!'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'value' => 'cache clear error: ' . $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
