<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Acarglobal\Controllers\AcarController;

/**
 * use __invoke function in controller
 * controller use __invoke when the controller has one action
 * define route not input controller function the process will use __invoke function in controller
 */
// Route::get('acar', AcarController::class,);

Route::get('add-car', [AcarController::class, 'addCar']);
