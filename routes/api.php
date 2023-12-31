<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\v1\SafetyContractorController;
use App\Http\Controllers\Admin\v1\SaftyConsultantController;
use App\Http\Controllers\Admin\v1\StepController;
use App\Http\Controllers\Admin\v1\SupervisorController;
use App\Http\Controllers\Admin\v1\SupervisorProvinceController;
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
   Route::put('/supervisor/{id}',[SupervisorController::class,'update']);
   Route::delete('/supervisor/{id}',[SupervisorController::class,'destroy']);

    Route::get('/supervisor-province',[SupervisorProvinceController::class,'index']);
    Route::post('/supervisor-province',[SupervisorProvinceController::class,'store']);
    Route::get('/supervisor-province/{id}',[SupervisorProvinceController::class,'show']);
    Route::put('/supervisor-province/{id}',[SupervisorProvinceController::class,'update']);
    Route::delete('/supervisor-province/{id}',[SupervisorProvinceController::class,'destroy']);

    Route::get('/safety_consultant',[SaftyConsultantController::class,'index']);
    Route::post('/safety_consultant',[SaftyConsultantController::class,'store']);
    Route::get('/safety_consultant/{id}',[SaftyConsultantController::class,'show']);
    Route::put('/safety_consultant/{id}',[SaftyConsultantController::class,'update']);
    Route::delete('/safety_consultant/{id}',[SaftyConsultantController::class,'destroy']);

    Route::get('/safety_contractor',[SafetyContractorController::class,'index']);
    Route::post('/safety_contractor',[SafetyContractorController::class,'store']);
    Route::get('/safety_contractor/{id}',[SafetyContractorController::class,'show']);
    Route::put('/safety_contractor/{id}',[SafetyContractorController::class,'update']);
    Route::delete('/safety_contractor/{id}',[SafetyContractorController::class,'destroy']);

    Route::get('/step',[StepController::class,'index']);
    Route::put('/step/{id}',[StepController::class,'update']);
});
