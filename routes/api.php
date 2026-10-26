<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
});

Route::get('hash/{text}', function (string $text) {
    return Hash::make($text);
});
Route::get('integrasi/check', 'IntegrasiController@check');
Route::namespace('App\\Http\\Controllers\\Api')->group(function () {
    Route::get('integrasi/check', 'IntegrasiController@check');
    Route::get('integrasi/getlistunitkerja', 'IntegrasiController@getListUnitKerja');
    Route::get('integrasi/getintervalrecording', 'IntegrasiController@getIntervalRecording');
     Route::post('integrasi/sendfilesound', 'IntegrasiController@storefile');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('integrasi/setflagunitkerja', 'IntegrasiController@setFlagUnitKerja');
        Route::post('integrasi/upsertunitkerjaaktif', 'IntegrasiController@insUnitKerjaAktif');
        Route::put('integrasi/upduserlocalinfo', 'IntegrasiController@updUserLocalInfo');
        Route::post('integrasi/insloggerlocal', 'IntegrasiController@insLoggerLocal');
       
        Route::get('integrasi/getlocalpassword', 'IntegrasiController@getLocalPassword');
        Route::get('integrasi/getlistserialnumber', 'IntegrasiController@getListSerialNumber');
        Route::post('integrasi/insstatusserverlocal', 'IntegrasiController@insStsSrvLocal');
    });    
});
