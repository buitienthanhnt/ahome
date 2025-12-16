<?php

use Illuminate\Support\Facades\Route;


Route::get('amua', [Thanhnt\Amuaglobal\Controllers\DemoController::class, 'docData']);