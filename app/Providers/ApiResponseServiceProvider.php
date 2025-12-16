<?php

namespace App\Providers;

use App\Api\Data\ResponseData;
use App\Api\ResponseApi;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ResponseApi::class, function (Application $app) {
            return new ResponseApi;
        });

        $this->app->singleton(ResponseData::class, function (Application $app) {
            $request = $app->make('request');
            return new ResponseData($request);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
