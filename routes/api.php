<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\InstallmentPlan\InstallmentPlanController;

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
        Route::post('send-password-reset-link', 'sendPasswordResetLink');
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

            // Inquiry Officer Management
            Route::post('add-inquiry-officer/{employee_id}', 'addInquiryOfficer');
            Route::delete('delete-inquiry-officer/{employee_id}', 'deleteInquiryOfficer');
            Route::get('get-inquiry-officers', 'getInquiryOfficers');

            // Recovery Officer Management
            Route::post('add-recovery-officer/{employee_id}', 'addRecoveryOfficer');
            Route::delete('delete-recovery-officer/{employee_id}', 'deleteRecoveryOfficer');
            Route::get('get-recovery-officers', 'getRecoveryOfficers');

            // Branch Management
            Route::post('add-branch', 'storeBranch');
            Route::get('/get-all-branches', 'getBranches');
            Route::get('get-branch/{branch_id}', 'getBranch');
            Route::get('get-branch-employees/{branch_id}', 'getBranchEmployees');

            // User Management
            Route::delete('delete-user/{user_id}', 'deleteUser');
            Route::get('get-company', 'getcompany');

            // Inventory Management
            Route::controller(InventoryController::class)->group(function () {
                Route::get('get-all-categories', 'getAllCategories');
                Route::get('get-category/{id}', 'getCategoryById');
                Route::get('get-all-brands', 'getAllBrands');
                Route::get('get-brand/{id}', 'getBrandById');
                Route::get('get-brands-by-category/{categoryName}', 'getBrandsByCategoryName');
                Route::post('store-inventory', 'storeInventory');
                Route::put('update-inventory/{id}', 'updateInventory');
                Route::delete('delete-inventory/{id}', 'deleteInventory');
                Route::get('get-all-inventory', 'getAllInventory');
                Route::get('get-inventory/{id}', 'getInventoryById');
                Route::get('get-inventory-by-brand/{brandName}', 'getInventoryByBrandName');
                Route::get('get-inventory-by-category/{categoryName}', 'getInventoryByCategoryName');
            });

            // Installment Plan Management
            Route::controller(InstallmentPlanController::class)->group(function () {
                Route::post('store-installment-plan', 'store');
                Route::put('update-installment-plan/{id}', 'update');
                Route::delete('delete-installment-plan/{id}', 'destroy');
            });

            // Customer Management
            Route::controller(CustomerController::class)->group(function () {
                Route::get('get-all-customers', 'index');
                Route::get('get-rejected-customers', 'getRejectedCustomers');
                Route::put('update-guarantor/{id}', 'updateGuarantor');
                Route::delete('delete-customer/{id}', 'destroy');
            });
        });

        Route::middleware(['role:admin|company admin|branch admin|employee'])->group(function () {
            // Customer Management
            Route::controller(CustomerController::class)->group(function () {
                Route::post('add-customer', 'store');
                Route::get('get-customers-without-guarantors', 'getCustomersWithoutGuarantors');
                Route::get('get-branch-customers', 'getBranchCustomers');
                Route::post('update-customer/{id}', 'update');
                Route::get('get-customer/{id}', 'show');
                Route::post('add-guarantor', 'addGuarantor');
                Route::get('get-inquiry-customers', 'getInquiryCustomers');
            });
            Route::controller(InstallmentPlanController::class)->group(function () {
                Route::get('get-all-installment-plans', 'index');
                Route::get('get-installment-plan/{id}', 'show');
            });
            Route::controller(AdminController::class)->group(function () {
                Route::get('get-company', 'getcompany');
            });
        });
        // Public Routes
        Route::post('password-setup', 'passwordSetup');
    });
});
