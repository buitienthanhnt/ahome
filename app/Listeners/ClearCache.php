<?php

// https://chungnguyen.xyz/laravel/docs/events.md

namespace App\Listeners;

use App\Events\CacheClear;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;


class ClearCache implements ShouldQueue
{
    use InteractsWithQueue;

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
     * @param  \App\Events\CacheClear  $event
     * @return void
     */
    public function handle(CacheClear $event)
    {
        if ($event->type) { // bắt param trong event.
            if (is_array($event->type)) {
                foreach ($event->type as $value) {
                    Cache::forget($value);
                }
            } else {
                Cache::forget($event->type);
            }
        } else {
            Cache::flush();
        }
    }
}
