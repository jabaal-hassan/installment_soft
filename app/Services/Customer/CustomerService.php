<?php

namespace App\Services\Customer;

use App\Models\Sale;
use App\Models\Branch;
use App\Helpers\Helpers;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Guarantor;
use App\Models\Inventory;
use App\Constants\Messages;
use Illuminate\Http\Response;
use App\Models\CustomerAccount;
use App\Models\InstallmentPlan;
use App\DTOs\CustomerDTOs\SaleDTO;
use Illuminate\Support\Facades\DB;
use App\DTOs\CustomerDTOs\CustomerCreateDTO;
use App\DTOs\CustomerDTOs\CustomerAccountDTO;
use App\DTOs\CustomerDTOs\GuarantorCreateDTO;

class CustomerService
{
    /************************************ get All Customer ************************************/
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
    /************************************ get Customer ************************************/
    public function getCustomerById($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $customer->cnic_Front_image = $this->getFullUrl($customer->cnic_Front_image);
            $customer->cnic_Back_image = $this->getFullUrl($customer->cnic_Back_image);
            $customer->customer_image = $this->getFullUrl($customer->customer_image);
            $customer->check_image = $this->getFullUrl($customer->check_image);
            $customer->video = $this->getFullUrl($customer->video);


            // Sale ka data fetch karna aur brand/category ka name include karna
            $sale = Sale::where('customer_id', $id)
                ->with(['brand:id,name', 'category:id,name']) // Relationships load karna
                ->first();

            if ($sale) {
                $sale->brand_name = $sale->brand->name ?? null;
                $sale->category_name = $sale->category->name ?? null;
                unset($sale->brand, $sale->category, $sale->brand_id, $sale->category_id); // ID fields hata dena
            }


            $customerAccount = CustomerAccount::where('customer_id', $id)->first();

            $guarantors = Guarantor::where('customer_id', $id)->first();
            if (!$guarantors) {
                return Helpers::result('Guarantor not found', Response::HTTP_NOT_FOUND);
            }
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
    /************************************ get Branch Customer ************************************/
    public function getConfirmedCustomers($branchId)
    {
        try {
            $customers = Customer::where('branch_id', $branchId)->where('status', 'confirmed')
                ->get()->map(function ($customer) {
                    $customer->cnic_Front_image = $this->getFullUrl($customer->cnic_Front_image);
                    $customer->cnic_Back_image = $this->getFullUrl($customer->cnic_Back_image);
                    $customer->customer_image = $this->getFullUrl($customer->customer_image);
                    $customer->check_image = $this->getFullUrl($customer->check_image);
                    $customer->video = $this->getFullUrl($customer->video);
                    if ($customer->sell_officer_id) {
                        $sellOfficer = Employee::find($customer->sell_officer_id);
                        $customer->sell_officer = $sellOfficer ? [
                            'name' => $sellOfficer->name,
                            'father_name' => $sellOfficer->father_name,
                            'phone_number' => $sellOfficer->phone_number,
                        ] : null;
                    } else {
                        $customer->sell_officer = null;
                    }
                    if ($customer->inquiry_officer_id) {
                        $inquiryofficer = Employee::find($customer->inquiry_officer_id);
                        $customer->inquiry_officer = $inquiryofficer ? [
                            'name' => $inquiryofficer->name,
                            'father_name' => $inquiryofficer->father_name,
                            'phone_number' => $inquiryofficer->phone_number,
                        ] : null;
                    } else {
                        $customer->sell_officer = null;
                    }
                    return $customer;
                });

            return Helpers::result('Branch customers retrieved successfully', Response::HTTP_OK, ['customers' => $customers]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /************************************ get Branch Customer ************************************/
    public function getBranchCustomers($branchId)
    {
        try {
            $customers = Customer::where('branch_id', $branchId)->where('status', 'confirmed')
                ->get()->map(function ($customer) {
                    $customer->cnic_Front_image = $this->getFullUrl($customer->cnic_Front_image);
                    $customer->cnic_Back_image = $this->getFullUrl($customer->cnic_Back_image);
                    $customer->customer_image = $this->getFullUrl($customer->customer_image);
                    $customer->check_image = $this->getFullUrl($customer->check_image);
                    $customer->video = $this->getFullUrl($customer->video);
                    if ($customer->sell_officer_id) {
                        $sellOfficer = Employee::find($customer->sell_officer_id);
                        $customer->sell_officer = $sellOfficer ? [
                            'name' => $sellOfficer->name,
                            'father_name' => $sellOfficer->father_name,
                            'phone_number' => $sellOfficer->phone_number,
                        ] : null;
                    } else {
                        $customer->sell_officer = null;
                    }
                    if ($customer->inquiry_officer_id) {
                        $inquiryofficer = Employee::find($customer->inquiry_officer_id);
                        $customer->inquiry_officer = $inquiryofficer ? [
                            'name' => $inquiryofficer->name,
                            'father_name' => $inquiryofficer->father_name,
                            'phone_number' => $inquiryofficer->phone_number,
                        ] : null;
                    } else {
                        $customer->sell_officer = null;
                    }
                    return $customer;
                });

            return Helpers::result('Branch customers retrieved successfully', Response::HTTP_OK, ['customers' => $customers]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /************************************ get Branch Customer ************************************/
    public function getRejectedCustomers($branchId)
    {
        try {
            $customers = Customer::where('branch_id', $branchId)->where('status', 'rejected')
                ->get()->map(function ($customer) {
                    $customer->cnic_Front_image = $this->getFullUrl($customer->cnic_Front_image);
                    $customer->cnic_Back_image = $this->getFullUrl($customer->cnic_Back_image);
                    $customer->customer_image = $this->getFullUrl($customer->customer_image);
                    $customer->check_image = $this->getFullUrl($customer->check_image);
                    $customer->video = $this->getFullUrl($customer->video);
                    if ($customer->sell_officer_id) {
                        $sellOfficer = Employee::find($customer->sell_officer_id);
                        $customer->sell_officer = $sellOfficer ? [
                            'name' => $sellOfficer->name,
                            'father_name' => $sellOfficer->father_name,
                            'phone_number' => $sellOfficer->phone_number,
                        ] : null;
                    } else {
                        $customer->sell_officer = null;
                    }
                    if ($customer->inquiry_officer_id) {
                        $inquiryofficer = Employee::find($customer->inquiry_officer_id);
                        $customer->inquiry_officer = $inquiryofficer ? [
                            'name' => $inquiryofficer->name,
                            'father_name' => $inquiryofficer->father_name,
                            'phone_number' => $inquiryofficer->phone_number,
                        ] : null;
                    } else {
                        $customer->sell_officer = null;
                    }
                    return $customer;
                });

            return Helpers::result('Branch customers retrieved successfully', Response::HTTP_OK, ['customers' => $customers]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /************************************ Get Inquiry Customer ************************************/
    public function getInquiryCustomers()
    {
        try {
            $user = auth()->user();
            $role = $user->roles->first()->name ?? 'N/A';
            $employeeId = $user->employee->id ?? null;

            // Fetch customers based on role
            $query = Customer::where('status', 'processing');

            if ($role === 'employee' && $employeeId) {
                $query->where('inquiry_officer_id', $employeeId);
            }

            $customers = $query->get()->map(function ($customer) use ($role) {
                $customer->cnic_Front_image = $this->getFullUrl($customer->cnic_Front_image);
                $customer->cnic_Back_image = $this->getFullUrl($customer->cnic_Back_image);
                $customer->customer_image = $this->getFullUrl($customer->customer_image);
                $customer->check_image = $this->getFullUrl($customer->check_image);
                $customer->video = $this->getFullUrl($customer->video);
                if ($customer->sell_officer_id) {
                    $sellOfficer = Employee::find($customer->sell_officer_id);
                    $customer->sell_officer = $sellOfficer ? [
                        'name' => $sellOfficer->name,
                        'father_name' => $sellOfficer->father_name,
                        'phone_number' => $sellOfficer->phone_number,
                    ] : null;
                } else {
                    $customer->sell_officer = null;
                }
                // Add Inquiry Officer Details if the user is a Branch Admin
                if ($customer->inquiry_officer_id && $role === 'branch admin') {
                    $inquiryOfficer = Employee::find($customer->inquiry_officer_id);
                    $customer->inquiry_officer = $inquiryOfficer ? [
                        'id' => $inquiryOfficer->id,
                        'name' => $inquiryOfficer->name,
                        'father_name' => $inquiryOfficer->father_name,
                        'phone_number' => $inquiryOfficer->phone_number,
                    ] : null;
                } else {
                    $customer->inquiry_officer = null;
                }

                return $customer;
            });

            return Helpers::result('Inquiry Customers retrieved successfully', Response::HTTP_OK, ['customers' => $customers]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /************************************ Create Customer ************************************/
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
    /************************************ Update Customer ************************************/
    public function updateCustomer($id, $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $employee = $user->employee;
            $role = $user->roles->first()->name ?? 'N/A';
            $customer = Customer::findOrFail($id);

            if ($request->status === 'confirmed') {
                $guarantorExists = Guarantor::where('customer_id', $id)->exists();
                if (!$guarantorExists) {
                    return Helpers::result('Guarantor is required before confirming the customer', Response::HTTP_BAD_REQUEST);
                }
            }

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
            $customerData = array_merge(
                $customer->toArray(),
                array_filter($request->all(), fn($value, $key) => !is_null($value) && $key !== 'request_log_id', ARRAY_FILTER_USE_BOTH)
            );
            $customer->update($customerData);

            if ($request->status === 'confirmed') {
                Sale::where('customer_id', $id)->update(['status' => 'confirmed']);
                CustomerAccount::where('customer_id', $id)->update(['status' => 'confirmed']);
            }

            DB::commit();
            return Helpers::result('Customer updated successfully', Response::HTTP_OK, ['customer' => $customer]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /************************************ delete Customer ************************************/
    public function deleteCustomer($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            Sale::where('customer_id', $id)->delete();
            CustomerAccount::where('customer_id', $id)->delete();
            Guarantor::where('customer_id', $id)->delete();
            $customer->delete();
            return Helpers::result('Customer deleted successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /************************************ Get Customers Without Guarantors ************************************/

    public function getCustomersWithoutGuarantors()
    {
        try {
            $user = auth()->user();
            $role = $user->roles->first()->name ?? 'N/A';
            $employee = $user->employee;

            $customersQuery = Customer::doesntHave('guarantors');
            if ($role == 'employee') {
                $customersQuery->where('sell_officer_id', $employee->id);
            }

            $customers = $customersQuery->get()->map(function ($customer) {
                $customer->cnic_Front_image = $this->getFullUrl($customer->cnic_Front_image);
                $customer->cnic_Back_image = $this->getFullUrl($customer->cnic_Back_image);
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
