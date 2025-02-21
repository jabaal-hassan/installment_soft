<?php

namespace App\Services\Customer;

use App\Models\Sale;
use App\Models\Branch;
use App\Models\Guarantor;
use App\Helpers\Helpers;
use App\Models\Customer;
use App\Models\Inventory;
use App\Constants\Messages;
use Illuminate\Http\Response;
use App\Models\CustomerAccount;
use App\Models\InstallmentPlan;
use App\DTOs\CustomerDTOs\SaleDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\DTOs\CustomerDTOs\CustomerCreateDTO;
use App\DTOs\CustomerDTOs\CustomerAccountDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\DTOs\CustomerDTOs\GuarantorCreateDTO;

class CustomerService
{
    public function getAllCustomers()
    {
        try {
            $customers = Customer::all()->map(function ($customer) {
                $customer->cnic_Front_image = $this->getFullUrl($customer->cnic_Front_image);
                $customer->cnic_Back_image = $this->getFullUrl($customer->cnic_Back_image);
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

            $customer->cnic_Front_image = $this->getFullUrl($customer->cnic_Front_image);
            $customer->cnic_Back_image = $this->getFullUrl($customer->cnic_Back_image);
            $customer->customer_image = $this->getFullUrl($customer->customer_image);
            $customer->check_image = $this->getFullUrl($customer->check_image);
            $customer->video = $this->getFullUrl($customer->video);


            $sale = Sale::where('customer_id', $id)->first();

            $customerAccount = CustomerAccount::where('customer_id', $id)->first();

            $guarantors = Guarantor::where('customer_id', $id)->first();
            $guarantors->cnic_Front_image = $this->getFullUrl($guarantors->cnic_Front_image);
            $guarantors->cnic_Back_image = $this->getFullUrl($guarantors->cnic_Back_image);

            return Helpers::result('Customer retrieved successfully', Response::HTTP_OK, [
                'customer' => $customer,
                'sale' => $sale,
                'customerAccount' => $customerAccount,
                'guarantors' => $guarantors
            ]);
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

            $inventory = Inventory::where('item_name', $request->item_name)->where('model', $request->model)->first();
            if (!$inventory) {
                return Helpers::result('Product not found in inventory', Response::HTTP_BAD_REQUEST);
            }


            // Create customer
            $customerDTO = new CustomerCreateDTO($request, $employee);
            $customer = Customer::create($customerDTO->toArray());

            // Get installment plan details
            $installmentPlan = InstallmentPlan::find($request->installment_plan_id);
            if (!$installmentPlan) {
                return Helpers::result('Product not found in installment Plan', Response::HTTP_BAD_REQUEST);
            }

            // Create customer account
            $customerAccountDTO = new CustomerAccountDTO($request, $customer->id, $installmentPlan);
            $customerAccount = CustomerAccount::create($customerAccountDTO->toArray());

            // Create sale
            $saleDTO = new SaleDTO($inventory, $installmentPlan, $customer->id, $employee);
            $sale = Sale::create($saleDTO->toArray());

            // Delete inventory
            $inventory->delete();

            DB::commit();
            return Helpers::result('Customer created successfully', Response::HTTP_CREATED, [
                'customer' => $customer,
                'sale' => $sale,
                'customerAccount' => $customerAccount
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

    /************************************ Get Customers Without Guarantors ************************************/

    public function getCustomersWithoutGuarantors()
    {
        try {
            $customers = Customer::doesntHave('guarantors')->get()->map(function ($customer) {
                $customer->cnic_front_image = $this->getFullUrl($customer->cnic_front_image);
                $customer->cnic_back_image = $this->getFullUrl($customer->cnic_back_image);
                $customer->customer_image = $this->getFullUrl($customer->customer_image);
                $customer->check_image = $this->getFullUrl($customer->check_image);
                $customer->video = $this->getFullUrl($customer->video);
                return $customer;
            });

            return Helpers::result('Customers without guarantors retrieved successfully', Response::HTTP_OK, ['customers' => $customers]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /************************************ Add Guarantor ************************************/

    public function addGuarantor($request)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::find($request->customer_id);
            if (!$customer) {
                return Helpers::result('Customer not found', Response::HTTP_NOT_FOUND);
            }
            $guarantorCount = Guarantor::where('cnic', $request->cnic)->count();
            if ($guarantorCount >= 2) {
                return Helpers::result('Guarantor can only provide two guarantees', Response::HTTP_BAD_REQUEST);
            } else {
                $guarantorDTO = new GuarantorCreateDTO($request);
                $guarantor = Guarantor::create($guarantorDTO->toArray());
            }

            DB::commit();
            return Helpers::result('Guarantor added successfully', Response::HTTP_CREATED, $guarantor);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /************************************ Update Guarantor ************************************/

    public function updateGuarantor($id, $request)
    {
        try {
            DB::beginTransaction();

            $guarantor = Guarantor::find($id);
            if (!$guarantor) {
                return Helpers::result('Guarantor not found', Response::HTTP_NOT_FOUND);
            }

            $guarantorDTO = new GuarantorCreateDTO($request);
            $guarantor->update($guarantorDTO->toArray());

            DB::commit();
            return Helpers::result('Guarantor updated successfully', Response::HTTP_OK, $guarantor);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getFullUrl($path)
    {
        return !empty($path) ? (filter_var($path, FILTER_VALIDATE_URL) ? $path : asset('storage/' . $path)) : null;
    }
}
