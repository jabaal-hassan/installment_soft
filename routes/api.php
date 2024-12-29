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

    // Admin Routes
    Route::controller(AdminController::class)->group(function () {
        // Admin Only Routes
        Route::middleware(['role:admin'])->group(function () {
            // Company Management
            Route::post('add-company', 'storeCompany');
            Route::get('get-all-company', 'getallcompany');
            Route::get('get-company-employee/{name}', 'fetchCompanyEmployees');
        });

        // Multi-role Routes
        Route::middleware(['role:admin|company admin|branch admin'])->group(function () {
            // Employee Management
            Route::post('add-employee', 'addEmployee');
            Route::get('get-all-employees', 'getAllEmployees');
            Route::get('get-employees/{phone_number}', 'getEmployees');
            Route::put('update-employee/{employee_id}', 'updateEmployee');

            // Branch Management
            Route::post('add-branch', 'storeBranch');
            Route::get('/get-all-branches', 'getBranches');
            Route::get('get-branch/{branch_id}', 'getBranch');
            Route::get('get-branch-employees/{branch_id}', 'getBranchEmployees');

            // User Management
            Route::delete('delete-user/{user_id}', 'deleteUser');
            Route::get('get-company/{id}', 'getcompany');
        });

        // Public Routes
        Route::post('password-setup', 'passwordSetup');
    });
});
