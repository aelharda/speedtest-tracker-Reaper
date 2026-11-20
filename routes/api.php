<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ISP Reaper status endpoint
Route::get('/isp-reaper/status', function () {
    return \App\Helpers\IspreaperHelper::getConfig();
})->middleware('auth:sanctum');