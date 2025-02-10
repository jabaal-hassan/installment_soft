<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Helpers\Helpers;
use App\DTOs\User\UserDTO;
use App\Constants\Messages;
use App\DTOs\User\PasswordDTO;
use App\DTOs\AuthDTOs\RegisterDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendPasswordResetLinkJob;
use App\Jobs\SendPasswordSetupEmailJob;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{


    /*********************************************  Register a new user ******************************************/
    public function register($request)
    {
        try {
            $user = new UserDTO($request);
            $user = User::create($user->toArray());
            return Helpers::result('User created Successfully', Response::HTTP_OK, $user);
        } catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of login
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    /*********************************************  Login a user ******************************************/

    public function login($request)
    {
        try {

            $credentials = $request->only(['email', 'password']);

            // Attempt to authenticate the user
            if (!$token = Auth::attempt($credentials)) {
                return Helpers::result(Messages::InvalidCredentials, Response::HTTP_UNAUTHORIZED);
            }
            $user = Auth::user();
            // Get user's roles and permissions
            $roles = $user->getRoleNames();
            $permissions = $user->getAllPermissions()->pluck('name');

            $response = [
                'token' => Helpers::respondWithToken($token)['token'],
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $roles,
                    'permissions' => $permissions,
                ],
            ];
            return Helpers::result('Login Successfully', Response::HTTP_OK, $response);
        } catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of logout
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return Helpers::result(Messages::UserLoggedOut, Response::HTTP_OK);
    }

    /********************************************  Get user profile ******************************************/

    public function profile()
    {
        if (!Auth::check()) {
            return Helpers::result("Unauthorized. User is not logged in.", Response::HTTP_NOT_FOUND);
        }
        return Helpers::result('User Profile', Response::HTTP_OK, [Auth::user()]);
    }

    /********************************************  Send Password Reset Link ******************************************/

    public function sendPasswordResetLink($request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return Helpers::result('User not found', Response::HTTP_NOT_FOUND);
            }
            $token = app('auth.password.broker')->createToken($user);

            // Save the token in the remember_token field
            $user->remember_token = $token;
            $user->save();

            SendPasswordSetupEmailJob::dispatch($user, $token);
            return Helpers::result('Password reset link sent', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /********************************************  Reset Password ******************************************/

    public function resetPassword($request)
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
}
