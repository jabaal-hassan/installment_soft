<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\AdminService;
use App\Http\Requests\Admin\EmployeeRequest;
use App\Http\Requests\Admin\PasswordRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Http\Requests\Admin\BranchRequest;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function storeCompany(StoreCompanyRequest $request): JsonResponse
    {
        return $this->adminService->storeCompany($request);
    }
    public function getallcompany()
    {
        return $this->adminService->getallcompany();
    }
    public function getcompany()
    {
        return $this->adminService->getcompany();
    }
    public function fetchCompanyEmployees($companyName, Request $request)
    {
        return $this->adminService->getCompanyEmployees($companyName, $request);
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

    public function getEmployees(Request $request, $id)
    {
        return $this->adminService->getEmployees($request, $id);
    }

    public function deleteUser(Request $request, $user_id): JsonResponse
    {
        return $this->adminService->deleteUserAndEmployee($request, $user_id);
    }

    public function updateEmployee(UpdateEmployeeRequest $request, $employee_id): JsonResponse
    {

        return $this->adminService->updateEmployee($request, $employee_id);
    }

    public function getAllEmployees(): JsonResponse
    {
        return $this->adminService->getAllEmployees();
    }

    public function storeBranch(BranchRequest $request): JsonResponse
    {
        return $this->adminService->storeBranch($request);
    }

    public function getBranchEmployees($branch_id)
    {
        return $this->adminService->getBranchEmployees($branch_id);
    }

    public function getBranch($branch_id)
    {
        return $this->adminService->getBranch($branch_id);
    }

    public function getBranches()
    {
        return $this->adminService->getAllBranches();
    }
}
