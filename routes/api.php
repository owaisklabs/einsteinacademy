<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InitController;
use App\Http\Controllers\PastPaperController;
use App\Http\Controllers\StudyMaterialController;
use App\Http\Controllers\StudyNoteController;
use App\Http\Controllers\UserActivity;
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

    Route::apiResource('/study-notes',StudyNoteController::class);

    Route::get('past-paper',[PastPaperController::class,'indexApi']);
    Route::get('past-paper/{id}',[PastPaperController::class,'showApi']);

    Route::post('rating',[UserActivity::class,'rating']);
    Route::get('follow/{id}',[UserActivity::class,'follow']);
    Route::get('user-profile/{id}',[UserActivity::class,'userProfile']);
    Route::put('user-profile-update/{id}',[UserActivity::class,'userProfileUpdate']);
    Route::post('profile-pic-update/{id}',[UserActivity::class,'profilePicUpdate']);

    Route::post('zoom-event',[UserActivity::class,'createZoomEvent']);
    Route::get('zoom-event',[UserActivity::class,'getZoomEvents']);
});
// Route::get('/',[AuthController::class,'test']);
Route::get('get-data',[InitController::class,'getData']);
Route::post('signup',[AuthController::class,'register']);
Route::post('resend-otp',[AuthController::class,'resendOtp']);
Route::post('otp-verify',[AuthController::class,'verifyOtp']);
Route::post('signin',[AuthController::class,'SignIn']);
Route::post('forget-password',[AuthController::class,'forgetPassword']);
Route::post('otp-verify-forget-password',[AuthController::class,'verifyOtpForgetPassword']);

