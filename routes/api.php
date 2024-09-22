<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\v1\AuthContractorController;
use App\Http\Controllers\Admin\v1\CityeController;
use App\Http\Controllers\Admin\v1\ContractController;
use App\Http\Controllers\Admin\v1\ContractorRequestController;
use App\Http\Controllers\Admin\v1\ContractorRequestCycleController;
use App\Http\Controllers\Admin\v1\ExpertController;
use App\Http\Controllers\Admin\v1\ProvinceController;
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

    Route::get('/province',[ProvinceController::class,'index']);
    Route::get('/city/{id}',[CityeController::class,'index']);
    Route::get('/expert',[ExpertController::class,'index']);
    Route::post('/contractor-request',[ContractorRequestController::class,'store']);
    Route::get('/contractor-request-show',[ContractorRequestController::class,'show']);
    Route::get('/contract_show_one/{id}',[ContractorRequestController::class,'contract_show_one']);
    Route::get('/contractor-request-road',[ContractController::class,'contractor_request_road']);
    Route::get('/contractor-request-road/{id}',[ContractorRequestController::class,'contractor_request_road_id']);
    Route::post('/contractor-request-road-importance',[ContractorRequestController::class,'contractor_request_road_importance']);
    Route::post('/update_contractor_request_importance_status',[ContractorRequestController::class,'update_contractor_request_importance_status']);
    Route::post('/testjson',[ContractorRequestController::class,'testjsonvalidate']);
    Route::post('/checklist_all_request',[ContractController::class,'checklist_all_request']);
    Route::get('/contract_password',[ContractController::class,'get_contract_password']);
    Route::post('/show_contract_request',[ContractorRequestCycleController::class,'show_contract_request']);
    Route::post('/update_safety_consultant',[ContractorRequestCycleController::class,'update_safety_consultant']);

    Route::post('/login_contractor',[AuthContractorController::class,'login_contractor']);
    Route::post('/show_all_safety_consultant',[ContractController::class,'show_all_safety_consultant']);
    Route::get('/group_constant',[ContractController::class,'group_constant']);

});
