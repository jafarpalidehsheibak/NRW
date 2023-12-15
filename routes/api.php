<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\v1\SupervisorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'],function(){
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class,'login']);
        Route::post('/logout', [AuthController::class,'logout']);
        Route::post('/refresh', [AuthController::class,'refresh']);
        Route::post('/me', [AuthController::class,'me']);
    });
});

Route::group(['prefix'=>'v1'],function (){
   Route::get('/index',[NewsController::class,'index']);
   Route::get('/supervisor',[SupervisorController::class,'index']);
   Route::post('/supervisor',[SupervisorController::class,'store']);
   Route::get('/supervisor/{id}',[SupervisorController::class,'show']);
});
