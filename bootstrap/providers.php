<?php

return [
    App\Providers\AppServiceProvider::class,
    // Intervention\Image\ImageServiceProvider::class, // custom run: composer require intervention/image
    UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider::class,
    Thanhnt\Nan\NanServiceProvider::class,
    App\Providers\ApiResponseServiceProvider::class,
    App\Providers\HelperServiceProvider::class,
    Thanhnt\Amuaglobal\AmuaglobalProvider::class,
    Thanhnt\Acarglobal\AcarglobalProvider::class,
    App\Providers\ViewShareProvider::class,
    Thanhnt\Ahomeglobal\AhomeglobalProvider::class,
];
