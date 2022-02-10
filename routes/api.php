<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\StudyMaterialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:api')->group( function () {
    Route::get('/test',[AuthController::class,'test']);
    Route::apiResource('/study-material',StudyMaterialController::class);
});
// Route::get('/',[AuthController::class,'test']);
Route::post('signup',[AuthController::class,'register']);
Route::post('otp-verify',[AuthController::class,'verifyOtp']);
Route::post('signin',[AuthController::class,'SignIn']);
Route::post('forget-password',[AuthController::class,'forgetPassword']);
Route::post('otp-verify-forget-password',[AuthController::class,'verifyOtpForgetPassword']);

