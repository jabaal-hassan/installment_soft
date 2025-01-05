<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Admin\PasswordRequest;
use Illuminate\Http\Request; // Correct import

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle user registration.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->authService->register($request);
    }

    /**
     * Handle user login.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return $this->authService->login($request);
    }

    /**
     * Handle user logout.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $response = $this->authService->logout();
        return response()->json($response);
    }
    public function profile()
    {
        return $this->authService->profile();
    }
    /**
     * Handle sending password reset link.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendPasswordResetLink(Request $request): JsonResponse
    {
        return $this->authService->sendPasswordResetLink($request);
    }
}
