<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\AdminService;
use App\Http\Requests\Admin\EmployeeRequest;
use App\Http\Requests\Admin\PasswordRequest;
use App\Http\Requests\Admin\DepartmentRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function addEmployee(EmployeeRequest $request): JsonResponse
    {
        return $this->adminService->addEmployee($request);
    }

    public function passwordSetup(PasswordRequest $request)
    {
        $data = $request->only(['email', 'token', 'password']);
        return $this->adminService->passwordSetup($data);
    }

    public function getEmployees(Request $request, $phone_number)
    {
        return $this->adminService->getEmployees($request, $phone_number);
    }    

    public function deleteUser(Request $request, $user_id): JsonResponse
    {
        return $this->adminService->deleteUserAndEmployee($request, $user_id);
    }

    public function updateEmployee(UpdateEmployeeRequest $request, $employee_id): JsonResponse
    {

        return $this->adminService->updateEmployee($request, $employee_id);
    }

    public function getAllEmployees(Request $request): JsonResponse
    {
        return $this->adminService->getAllEmployees($request);
    }

    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        return $this->adminService->updateUser($request);
    }
}
