<?php

namespace Thanhnt\Nan;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class NanServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        // load route for api env
        Route::middleware('api')->prefix('api')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        });
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'nan');
    }

    public function register()
    {

    }
}
