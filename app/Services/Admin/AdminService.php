<?php

namespace App\Services\Admin;

use App\Models\User;
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


class AdminService
{

    public function addEmployee($request)
    {

        try {
            DB::beginTransaction();
            $dto = new UserDTO($request);
            $user = User::create($dto->toArray());

            $user->assignRole($dto->role);

            $employeeDto = new EmployeeCreateDTO($request, $user->id);
            $employee = Employee::create($employeeDto->toArray());
            SendPasswordSetupEmailJob::dispatch($user, $dto->remember_token);
            DB::commit();

            return Helpers::result(Messages::UserRegistered, Response::HTTP_OK, [
                'user' => $user,
                'employee' => $employee
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

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
    public function getEmployees($request, $phone_number)
    {
        try {
            $employees = Employee::where('phone_number', $phone_number)
                ->with(['user:id,name,email'])
                ->get(['id', 'user_id', 'position', 'date_of_joining', 'phone_number', 'id_card_number', 'address', 'pay']);

            // Check if no employees were found
            if ($employees->isEmpty()) {
                return Helpers::result(Messages::UserNotFound, Response::HTTP_NOT_FOUND);
            }

            // Format data for the response
            $formattedData = $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->user->name,
                    'email' => $employee->user->email,
                    'id_card_number' => $employee->id_cart_number,
                    'address' => $employee->address,
                    'position' => $employee->position,
                    'date_of_joining' => $employee->date_of_joining,
                    'phone_number' => $employee->phone_number,
                    'id_card_number' => $employee->id_card_number,
                    'address' => $employee->address,
                    'pay' => $employee->pay,
                ];
            });

            // Return success response
            return Helpers::result(Messages::EmployeesFetched, Response::HTTP_OK, [
                'phone_number' => $phone_number,
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
    public function getAllEmployees($request)
    {
        try {
            $employees = Employee::with(['user.roles'])->get();
            if ($employees->isEmpty()) {
                return Helpers::result(Messages::UserNotFound, Response::HTTP_NOT_FOUND);
            }

            $data = $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'user_id' => $employee->user_id,
                    'name' => $employee->user->name,
                    'position' => $employee->position,
                    'pay' => $employee->pay,
                    'date_of_joining' => $employee->date_of_joining,
                    'created_at' => $employee->created_at,
                    'updated_at' => $employee->updated_at,
                    'role' => $employee->user->roles->first()->name ?? 'N/A',
                ];
            });

            return Helpers::result(Messages::EmployeesFetched, Response::HTTP_OK, $data);
        } catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Summary of updateUser
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateUser($request)
    {
        try {
            $user = auth()->user();
            $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
            ]);

            return Helpers::result(Messages::UserUpdated, Response::HTTP_OK, null);
        } catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
