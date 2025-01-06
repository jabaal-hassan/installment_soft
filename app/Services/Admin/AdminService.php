<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Company;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\DTOs\User\UserDTO;
use App\Constants\Messages;
use App\DTOs\User\PasswordDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendPasswordSetupEmailJob;
use App\DTOs\EmployeeDTOs\EmployeeCreateDTO;
use App\DTOs\EmployeeDTOs\EmployeeUpdateDTO;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Models\Branch;
use App\DTOs\BranchDTOs\BranchCreateDTO;


class AdminService
{
    /************************************ Store Company  ************************************/

    public function storeCompany($request)
    {
        try {
            DB::beginTransaction();
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $timestamp = now()->format('YmdHs');
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

                // Store the file in 'public/logos' folder
                $file->storeAs('logos', $newFileName, 'public');
                $logoPath = 'logos/' . $newFileName;
            }

            // Create the company
            $company = Company::create([
                'name' => $request->name,
                'logo' => $logoPath,
            ]);
            DB::commit();

            return Helpers::result(Messages::CompanyCreated, Response::HTTP_CREATED, [
                'company' => $company
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /************************************ get all company  ************************************/

    public function getallcompany()
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            if (!empty($company->logo)) {
                $company->logo = filter_var($company->logo, FILTER_VALIDATE_URL)
                    ? $company->logo
                    : asset('storage/' . $company->logo);
            } else {
                $company->logo = null;
            }
        }


        return Helpers::result(Messages::getCompany, Response::HTTP_OK, [
            'companies' => $companies
        ]);
    }

    /************************************ get single company  ************************************/

    public function getcompany($id)
    {
        $company = Company::find($id);

        if (!empty($company->logo)) {
            $company->logo = filter_var($company->logo, FILTER_VALIDATE_URL)
                ? $company->logo
                : asset('storage/' . $company->logo);
        } else {
            $company->logo = null;
        }

        return Helpers::result(Messages::getCompany, Response::HTTP_OK, [
            'company' => $company
        ]);
    }

    /************************************ get Company Employees  ************************************/

    public function getCompanyEmployees($companyName, $request)
    {
        try {
            // Fetch the company by its name
            $company = Company::where('name', $companyName)->first();

            if (!$company) {
                return Helpers::result('Company not found', Response::HTTP_NOT_FOUND);
            }

            // Fetch employees for the found company ID
            $employees = Employee::with(['user.roles', 'company'])
                ->where('company_id', $company->id)
                ->get();

            if ($employees->isEmpty()) {
                return Helpers::result('No employees found for this company', Response::HTTP_NOT_FOUND);
            }

            // Format the data
            $data = $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'user_id' => $employee->user_id,
                    'name' => $employee->user->name,
                    'father_name' => $employee->father_name,
                    'position' => $employee->position,
                    'pay' => $employee->pay,
                    'date_of_joining' => $employee->date_of_joining,
                    'created_at' => $employee->created_at,
                    'updated_at' => $employee->updated_at,
                    'role' => $employee->user->roles->first()->name ?? 'N/A',
                    'company_name' => $employee->company->name ?? 'N/A',
                ];
            });

            return Helpers::result('Employees fetched successfully', Response::HTTP_OK, $data);
        } catch (\Throwable $e) {
            return Helpers::error($request, 'An exception occurred', $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /************************************ Store Branch  ************************************/

    public function storeBranch($request)
    {
        try {
            DB::beginTransaction();
            // Get company_id from request or from authenticated user
            $companyId = $request->company_id ?? auth()->user()->employee->company_id;

            // Add company_id to request data
            $request->merge(['company_id' => $companyId]);

            $dto = new BranchCreateDTO($request);
            $branch = Branch::create($dto->toArray());

            DB::commit();

            return Helpers::result(Messages::BranchCreated, Response::HTTP_CREATED, [
                'branch' => $branch
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /************************************ get single branch  ************************************/
    public function getBranch($id)
    {
        $branch = Branch::with('company')->find($id);

        if (!$branch) {
            return Helpers::result('Branch not found', Response::HTTP_NOT_FOUND);
        }

        return Helpers::result(Messages::getBranch, Response::HTTP_OK, [
            'branch' => $branch
        ]);
    }

    /************************************ get all branches  ************************************/
    public function getAllBranches()
    {
        $user = auth()->user();
        $role = $user->roles->first()->name ?? 'N/A';

        $query = Branch::with('company');

        // If user is company admin, show only their company's branches
        if ($role === 'company admin' || $role === 'branch admin') {
            $companyId = $user->employee->company_id;
            $query->where('company_id', $companyId);
        }
        // Super admin will see all branches (no filter needed)

        $branches = $query->get();

        if ($branches->isEmpty()) {
            return Helpers::result('No branches found', Response::HTTP_NOT_FOUND);
        }

        return Helpers::result(Messages::getBranches, Response::HTTP_OK, [
            'branches' => $branches
        ]);
    }

    /************************************ get Branch Employees  ************************************/
    public function getBranchEmployees($branch_id)
    {
        $branch = Branch::find($branch_id);

        if (!$branch) {
            return Helpers::result('Branch not found', Response::HTTP_NOT_FOUND);
        }

        $employees = Employee::with(['user.roles', 'company'])
            ->where('branch_id', $branch_id)
            ->get();

        if ($employees->isEmpty()) {
            return Helpers::result('No employees found for this branch', Response::HTTP_NOT_FOUND);
        }

        $data = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'user_id' => $employee->user_id,
                'name' => $employee->user->name,
                'father_name' => $employee->father_name,
                'position' => $employee->position,
                'pay' => $employee->pay,
                'date_of_joining' => $employee->date_of_joining,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
                'role' => $employee->user->roles->first()->name ?? 'N/A',
                'company_name' => $employee->company->name ?? 'N/A'
            ];
        });

        return Helpers::result('Branch employees fetched successfully', Response::HTTP_OK, $data);
    }

    /************************************ add Employees  ************************************/


    public function addEmployee($request)
    {
        try {
            DB::beginTransaction();

            $admin = auth()->user()->employee;

            $companyId = $request->company_id ?? $admin->company_id;
            $branchId = $request->branch_id ?? ($admin->branch_id);

            $dto = new UserDTO($request->all());
            $user = User::create($dto->toArray());

            $user->assignRole($dto->role);

            // Pass the actual $request object instead of an array
            $employeeDto = new EmployeeCreateDTO($request, $user->id, $companyId, $branchId ?? null); // Default to 0 if branchId is null
            $employee = Employee::create($employeeDto->toArray());

            SendPasswordSetupEmailJob::dispatch($user, $dto->remember_token);

            DB::commit();

            // Return successful response
            return Helpers::result(Messages::UserRegistered, Response::HTTP_OK, [
                'user' => $user,
                'employee' => $employee
            ]);
        } catch (\Throwable $e) {
            // Rollback transaction if error occurs
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /************************************ password Setup  ************************************/

    public function passwordSetup($request)
    {
        try {
            $dto = new PasswordDTO($request);

            $user = User::where('email', $dto->email)->first();

            if (!$user || $user->remember_token !== $dto->token) {
                return Helpers::result(Messages::InvalidCredentials, Response::HTTP_BAD_REQUEST);
            }
            $user->password = Hash::make($dto->password);
            $user->remember_token = null;
            $user->save();

            return Helpers::result(Messages::PasswordSetSuccess, Response::HTTP_OK);
        } catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Fetch employees by department ID
     * @param int $department_id
     * @return \Illuminate\Http\JsonResponse
     */


    /************************************ get Employees ************************************/

    public function getEmployees($request, $id)
    {
        try {
            $employees = Employee::where('id', $id)
                ->with(['user:id,name,email'])
                ->get();

            // Check if no employees were found
            if ($employees->isEmpty()) {
                return Helpers::result(Messages::UserNotFound, Response::HTTP_NOT_FOUND);
            }


            // Format data for the response
            $formattedData = $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'user_id' => $employee->user_id,
                    'name' => $employee->user->name,
                    'father name' => $employee->father_name,
                    'position' => $employee->position,
                    'pay' => $employee->pay,
                    'date_of_joining' => $employee->date_of_joining,
                    'created_at' => $employee->created_at,
                    'updated_at' => $employee->updated_at,
                    'role' => $employee->user->roles->first()->name ?? 'N/A',
                    'company_name' => $employee->company->name ?? 'N/A',
                    'branch_name' => $employee->branch->name ?? 'N/A',
                    'branch_city' => $employee->branch->city ?? 'N/A',
                    // Handle id_card_image
                    'id_card_image' => !empty($employee->id_card_image) ?
                        (filter_var($employee->id_card_image, FILTER_VALIDATE_URL)
                            ? $employee->id_card_image
                            : asset('storage/' . $employee->id_card_image))
                        : null,
                    // Handle check_image
                    'check_image' => !empty($employee->check_image) ?
                        (filter_var($employee->check_image, FILTER_VALIDATE_URL)
                            ? $employee->check_image
                            : asset('storage/' . $employee->check_image))
                        : null,
                ];
            });

            // Return success response
            return Helpers::result(Messages::EmployeesFetched, Response::HTTP_OK, [
                'employees' => $formattedData,
            ]);
        } catch (\Throwable $e) {
            // Handle exceptions
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Delete a user and their related employee record
     *
     * @param int $user_id
     * @return \Illuminate\Http\JsonResponse
     */

    /************************************ delete User And Employee ************************************/

    public function deleteUserAndEmployee($request, $user_id)
    {
        try {
            $user = User::find($user_id);

            if (!$user) {
                return Helpers::result(Messages::UserNotFound, Response::HTTP_NOT_FOUND);
            }

            Employee::where('user_id', $user_id)->delete();
            $user->delete();

            return Helpers::result(Messages::UserDeleted, Response::HTTP_OK);
        } catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of updateEmployee
     * @param mixed $data
     * @param mixed $employee_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    /************************************ update Employee ************************************/

    public function updateEmployee($request, $employee_id)
    {
        try {
            $employee = Employee::find($employee_id);

            if (!$employee) {
                return Helpers::result(Messages::UserNotFound, Response::HTTP_NOT_FOUND);
            }
            $dto = new EmployeeUpdateDTO($request);
            $employee->update($dto->toArray());

            return Helpers::result(Messages::EmployeeUpdated, Response::HTTP_OK, $employee);
        } catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * Summary of getAllEmployees
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    /************************************ get All Employees ************************************/

    public function getAllEmployees()
    {
        $user = auth()->user();
        $role = $user->roles->first()->name ?? 'N/A';

        $query = Employee::with(['user.roles', 'company']);
        if ($role === 'company admin') {
            $query->whereHas('user.roles', function ($q) {
                $q->where('name', '!=', 'Company admin');
            });
        }

        if ($role === 'branch admin') {
            $query->whereHas('user.roles', function ($q) {
                $q->whereNotIn('name', ['company admin', 'branch admin']);
            });
        }

        $employees = $query->get();

        if ($employees->isEmpty()) {
            return Helpers::result(Messages::UserNotFound, Response::HTTP_NOT_FOUND);
        }

        // Format the data
        $data = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'user_id' => $employee->user_id,
                'name' => $employee->user->name,
                'email' => $employee->user->email,
                'phone_number' => $employee->phone_number,
                'id_card_number' => $employee->id_card_number,
                'address' => $employee->address,
                'father name' => $employee->father_name,
                'position' => $employee->position,
                'pay' => $employee->pay,
                'date_of_joining' => $employee->date_of_joining,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
                'role' => $employee->user->roles->first()->name ?? 'N/A',
                'company_name' => $employee->company->name ?? 'N/A',
                'branch_name' => $employee->branch->name ?? 'N/A',
                'branch_city' => $employee->branch->city ?? 'N/A',
                // Handle id_card_image
                'id_card_image' => !empty($employee->id_card_image) ?
                    (filter_var($employee->id_card_image, FILTER_VALIDATE_URL)
                        ? $employee->id_card_image
                        : asset('storage/' . $employee->id_card_image))
                    : null,
                // Handle check_image
                'check_image' => !empty($employee->check_image) ?
                    (filter_var($employee->check_image, FILTER_VALIDATE_URL)
                        ? $employee->check_image
                        : asset('storage/' . $employee->check_image))
                    : null,
            ];
        });

        return Helpers::result(Messages::EmployeesFetched, Response::HTTP_OK, $data);
    }
}
