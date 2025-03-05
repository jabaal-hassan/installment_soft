<?php

namespace App\Http\Controllers\Customer;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerService;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Requests\Customer\GuarantorRequest;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        return $this->customerService->getAllCustomers();
    }

    public function store(CustomerRequest $request): JsonResponse
    {
        return $this->customerService->createCustomer($request);
    }

    public function show($id)
    {
        return $this->customerService->getCustomerById($id);
    }
    public function getRejectedCustomers()
    {
        $user = auth()->user();
        $branchId = $user->employee->branch_id;
        return $this->customerService->getRejectedCustomers($branchId);
    }

    public function getInquiryCustomers()
    {
        return $this->customerService->getInquiryCustomers();
    }

    public function update(Request $request, $id)
    {
        return $this->customerService->updateCustomer($id, $request);
    }

    public function destroy($id)
    {
        return $this->customerService->deleteCustomer($id);
    }

    public function getBranchCustomers()
    {
        $user = auth()->user();
        $branchId = $user->employee->branch_id;
        return $this->customerService->getBranchCustomers($branchId);
    }
    public function getConfirmedCustomers()
    {

        $user = auth()->user();
        $branchId = $user->employee->branch_id;
        return $this->customerService->getConfirmedCustomers($branchId);
    }
    public function getCustomersWithoutGuarantors()
    {
        return $this->customerService->getCustomersWithoutGuarantors();
    }
    public function addGuarantor(GuarantorRequest $request)
    {
        return $this->customerService->addGuarantor($request);
    }
    public function updateGuarantor(GuarantorRequest $request, $id)
    {
        return $this->customerService->updateGuarantor($id, $request);
    }
}
