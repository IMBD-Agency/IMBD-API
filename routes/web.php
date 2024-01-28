<?php

use App\Http\Controllers\IMBDCustomController;
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

Route::get('/', function () {
    return redirect('https://www.imbdagency.com');
});

Route::get('login', function () {
    return redirect('https://www.imbdagency.com')->name('login');
});

Route::post('login', function () {
    return redirect('https://www.imbdagency.com');
});

Route::get('delete_screenshot', [IMBDCustomController::class, 'delete_screenshot']);
Route::get('debug', [IMBDCustomController::class, 'debug']);
