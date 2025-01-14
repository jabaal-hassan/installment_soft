<?php

namespace App\Services\Customer;

use App\DTOs\CutomerDTOs\CustomerCreateDTO;
use App\Models\Customer;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helpers;
use App\Constants\Messages;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CustomerService
{
    public function getAllCustomers()
    {
        try {
            $customers = Customer::all()->map(function ($customer) {
                $customer->cnic_front_image = $this->getFullUrl($customer->cnic_front_image);
                $customer->cnic_back_image = $this->getFullUrl($customer->cnic_back_image);
                $customer->customer_image = $this->getFullUrl($customer->customer_image);
                $customer->check_image = $this->getFullUrl($customer->check_image);
                $customer->video = $this->getFullUrl($customer->video);
                return $customer;
            });

            return Helpers::result('Customers retrieved successfully', Response::HTTP_OK, ['customers' => $customers]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCustomerById($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->cnic_front_image = $this->getFullUrl($customer->cnic_front_image);
            $customer->cnic_back_image = $this->getFullUrl($customer->cnic_back_image);
            $customer->customer_image = $this->getFullUrl($customer->customer_image);
            $customer->check_image = $this->getFullUrl($customer->check_image);
            $customer->video = $this->getFullUrl($customer->video);
            return Helpers::result('Customer retrieved successfully', Response::HTTP_OK, ['customer' => $customer]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCustomer($request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $employee = $user->employee;
            $role = $user->roles->first()->name ?? 'N/A';

            // Check if the provided branch_id belongs to the user's company, except for admin
            $branchId = $request->branch_id ?? $employee->branch_id;
            if (!$branchId) {
                return Helpers::result('Branch ID is required', Response::HTTP_BAD_REQUEST);
            }

            if ($role !== 'admin' && $request->branch_id) {
                $branch = Branch::find($request->branch_id);
                if (!$branch || $branch->company_id != $employee->company_id) {
                    return Helpers::result('Invalid branch ID for the user\'s company', Response::HTTP_BAD_REQUEST);
                }
            }

            $customerDTO = new CustomerCreateDTO($request, $employee);
            $customer = Customer::create($customerDTO->toArray());

            DB::commit();
            return Helpers::result('Customer created successfully', Response::HTTP_CREATED, [
                'customer' => $customer
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCustomer($id, $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $employee = $user->employee;
            $role = $user->roles->first()->name ?? 'N/A';

            // Check if the provided branch_id belongs to the user's company, except for admin
            $branchId = $request->branch_id ?? $employee->branch_id;
            if (!$branchId) {
                return Helpers::result('Branch ID is required', Response::HTTP_BAD_REQUEST);
            }

            if ($role !== 'admin' && $request->branch_id) {
                $branch = Branch::find($request->branch_id);
                if (!$branch || $branch->company_id != $employee->company_id) {
                    return Helpers::result('Invalid branch ID for the user\'s company', Response::HTTP_BAD_REQUEST);
                }
            }
            $customer = Customer::findOrFail($id);
            $customerDTO = new CustomerCreateDTO($request, $employee);
            $customer->update($customerDTO->toArray());

            DB::commit();
            return Helpers::result('Customer updated successfully', Response::HTTP_OK, ['customer' => $customer]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCustomer($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return Helpers::result('Customer deleted successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBranchCustomers($branchId)
    {
        try {
            $customers = Customer::where('branch_id', $branchId)->get()->map(function ($customer) {
                $customer->cnic_front_image = $this->getFullUrl($customer->cnic_front_image);
                $customer->cnic_back_image = $this->getFullUrl($customer->cnic_back_image);
                $customer->customer_image = $this->getFullUrl($customer->customer_image);
                $customer->check_image = $this->getFullUrl($customer->check_image);
                $customer->video = $this->getFullUrl($customer->video);
                return $customer;
            });

            return Helpers::result('Branch customers retrieved successfully', Response::HTTP_OK, ['customers' => $customers]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getFullUrl($path)
    {
        return !empty($path) ? (filter_var($path, FILTER_VALIDATE_URL) ? $path : asset('storage/' . $path)) : null;
    }
}
