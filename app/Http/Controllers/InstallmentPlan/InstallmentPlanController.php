<?php

namespace App\Http\Controllers\InstallmentPlan;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\InstallmentPlan\InstallmentPlanService;
use App\Http\Requests\InstallmentPlan\InstallmentPlanRequest;

class InstallmentPlanController extends Controller
{
    protected $installmentPlanService;

    public function __construct(InstallmentPlanService $installmentPlanService)
    {
        $this->installmentPlanService = $installmentPlanService;
    }

    public function index()
    {
        return $this->installmentPlanService->getAllInstallmentPlans();
    }

    public function show($id)
    {
        return $this->installmentPlanService->getInstallmentPlanById($id);
    }

    public function store(InstallmentPlanRequest $request): JsonResponse
    {
        return $this->installmentPlanService->createInstallmentPlan($request);
    }

    public function update($id, InstallmentPlanRequest $request): JsonResponse
    {
        return $this->installmentPlanService->updateInstallmentPlan($id, $request);
    }

    public function destroy($id)
    {
        return $this->installmentPlanService->deleteInstallmentPlan($id);
    }
}
