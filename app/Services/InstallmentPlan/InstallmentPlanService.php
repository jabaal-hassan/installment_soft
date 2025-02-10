<?php

namespace App\Services\InstallmentPlan;

use App\Helpers\Helpers;
use App\Constants\Messages;
use Illuminate\Http\Response;
use App\Models\InstallmentPlan;
use Illuminate\Support\Facades\DB;
use App\DTOs\InstallmentPlanDTO\InstallmentPlanCreateDTO;

class InstallmentPlanService
{
    public function getAllInstallmentPlans()
    {
        try {
            $installmentPlans = InstallmentPlan::all();
            return Helpers::result('Installment plans retrieved successfully', Response::HTTP_OK, ['installmentPlans' => $installmentPlans]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getInstallmentPlanById($id)
    {
        try {
            $installmentPlan = InstallmentPlan::findOrFail($id);
            return Helpers::result('Installment plan retrieved successfully', Response::HTTP_OK, ['installmentPlan' => $installmentPlan]);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createInstallmentPlan($request)
    {
        try {
            DB::beginTransaction();

            $installmentPlanDTO = new InstallmentPlanCreateDTO($request);
            $installmentPlan = InstallmentPlan::create($installmentPlanDTO->toArray());

            DB::commit();
            return Helpers::result('Installment plan created successfully', Response::HTTP_CREATED, ['installmentPlan' => $installmentPlan]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateInstallmentPlan($id, $request)
    {
        try {
            DB::beginTransaction();

            $installmentPlan = InstallmentPlan::findOrFail($id);
            $installmentPlanDTO = new InstallmentPlanCreateDTO($request);
            $installmentPlan->update($installmentPlanDTO->toArray());

            DB::commit();
            $response = $installmentPlan->toArray();
            unset($response['Product_name']); // Remove duplicate key

            return Helpers::result('Installment plan updated successfully', Response::HTTP_OK, ['installmentPlan' => $response]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteInstallmentPlan($id)
    {
        try {
            $installmentPlan = InstallmentPlan::findOrFail($id);
            $installmentPlan->delete();
            return Helpers::result('Installment plan deleted successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return Helpers::error(null, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
