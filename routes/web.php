<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\PastPaperController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\ReportAnalyticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (Auth::user()) {
    if (Auth::user()->type == 'Admin') {
        redirect()->route('home');
    }
}

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => ['auth']], function () {
    Route::get('blocked-user/{id}',[UserController::class,'blockUser'])->name('blocked-user');
    Route::get('un-blocked-user/{id}',[UserController::class,'unBlockUser'])->name('un-blocked-user');

    Route::get('user/{id}', [UserController::class, 'show'])->name('user');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('/content', ContentController::class);
    Route::resource('/push-notification', PushNotificationController::class);
    Route::resource('/report-analytics', ReportAnalyticsController::class);
    Route::resource('/past-paper', PastPaperController::class);
    Route::get('report-user',[\App\Http\Controllers\UserActivity::class,'reportUser'])->name('report-user');
    Route::get('block-user',[\App\Http\Controllers\UserActivity::class,'blockUser'])->name('block-user');
});


Auth::routes();
