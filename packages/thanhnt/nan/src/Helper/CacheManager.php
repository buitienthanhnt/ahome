<?php

namespace Thanhnt\Nan\Helper;

use App\Enums\CacheStorage;
use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

final class CacheManager
{
    protected $request;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    /**
     * @param string                                  $key
     * @param Closure                                 $value
     * @param DateTimeInterface|DateInterval|int|null $time
     * @return mixed
     */
    public function getAndCache(string $key, $value = null, $time = null)
    {
        /**
         * clear old cache if the header has key: forget_cache
         */
        if ($this->request->header("forget_cache", false)) {
            Cache::forget($key);
        }
        if (env("CACHE_FOREVER")) {
            $time = CacheStorage::TIME_3_D;
        }

        try {
            /**
             * use redis cache.
             */
            if (env("CACHE_REDIS")){
                $value = Cache::store('redis')->remember($key, $time, $value);
                return $value;
            }
        } catch (\Exception $exception) {}
        /**
         * use for setting env: CACHE_FOREVER
         * or save by default setting.
         */
        return Cache::remember($key, $time, $value);
    }
}
