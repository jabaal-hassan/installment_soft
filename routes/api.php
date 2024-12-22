<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;

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

Route::group(['middleware' => ['api', 'log.request', 'log.activity']], function () {
    // Authentication Routes


    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('profile', 'profile');
    });


    Route::group(['middleware' => ['role:admin|branch admin']], function () {
        Route::post('add-employee', [AdminController::class, 'addEmployee']);
        Route::get('get-employees/{phone_number}', [AdminController::class, 'getEmployees']);
    });
    Route::post('password-setup', [AdminController::class, 'passwordSetup']);
});
