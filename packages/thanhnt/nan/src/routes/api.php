<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('routeList', function (){
    return [
        "a" => 123,
        'x'=> "lkajsdl"
    ];
})->name('routeList');
