<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Helpers\Helpers;
use App\Constants\Messages;
use App\DTOs\AuthDTOs\RegisterDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\DTOs\User\UserDTO;
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

            $response = [
                'token' => Helpers::respondWithToken($token)['token'],
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
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
}
