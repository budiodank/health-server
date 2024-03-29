<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\UserOtpController;
use App\Http\Controllers\Api\ProjectController;

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

	Route::group(['prefix' => 'project'], function(){
		Route::get('/',   [ProjectController::class, 'index']);
	    Route::post('/',   [ProjectController::class, 'store']);
	    Route::put('/{id}', [ProjectController::class, 'update']);
		Route::delete('/{id}', [ProjectController::class, 'destroy']);

		Route::group(['prefix' => 'tool'], function(){
			Route::get('/{id}',   [ProjectController::class, 'indexTool']);
			Route::post('/{id}',   [ProjectController::class, 'storeTool']);
			Route::get('/show/{id}',   [ProjectController::class, 'showTool']);
		});

		Route::group(['prefix' => 'param'], function(){
			Route::get('/{id}',   [ProjectController::class, 'indexParam']);
		});
	});
});


Route::get('/provinces', [RegionController::class, 'provinces']);
Route::get('/regencies/{name}', [RegionController::class, 'regencies']);
Route::get('/districts/{name}', [RegionController::class, 'districts']);

Route::group(['prefix' => 'userotp'], function(){
    Route::post('/',   [UserOtpController::class, 'store']);
    Route::post('/{id}',   [UserOtpController::class, 'update']);
});
