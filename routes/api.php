<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\EmployeeTrackingController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    //protected routes
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/users', [EmployeeTrackingController::class, 'index']);
        Route::post('/screenshots/store', [EmployeeTrackingController::class, 'screenshots_store']);
        Route::post('/app/activity/store', [EmployeeTrackingController::class, 'app_activity_store']);
        Route::post('/website/activity/store', [EmployeeTrackingController::class, 'website_activity_store']);
    });
});