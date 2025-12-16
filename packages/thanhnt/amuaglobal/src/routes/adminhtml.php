<?php

use Illuminate\Support\Facades\Route;
use Thanhnt\Amuaglobal\Controllers\AmuaController;

Route::prefix('adminhtml')->group(function () {
	Route::get('clear-cache', [AmuaController::class, 'clearCache']);
});
