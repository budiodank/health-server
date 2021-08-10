<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\UserOtpController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/profile', [AuthController::class, 'profile']);
	Route::post('/logout', [AuthController::class, 'logout']);
});


Route::get('/provinces', [RegionController::class, 'provinces']);
Route::get('/regencies/{name}', [RegionController::class, 'regencies']);
Route::get('/districts/{name}', [RegionController::class, 'districts']);

Route::group(['prefix' => 'userotp'], function(){
    Route::post('/',   [UserOtpController::class, 'store']);
    Route::post('/{id}',   [UserOtpController::class, 'update']);
});
