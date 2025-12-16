<?php

namespace App\Listeners;

use App\Enums\CacheStorage;
use App\Events\CacheFrontEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class CacheFrontListen
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CacheFrontEvent  $event
     * @return void
     */
    public function handle(CacheFrontEvent $event)
    {
        $reflectionClass = new \ReflectionClass(CacheStorage::class);
        $allCacheKey = $reflectionClass->getConstants();
        foreach ($allCacheKey as $item) {
            if (Cache::has($item)){
                Cache::forget($item);
            }
        }
    }
}
