<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTOs;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserDTO extends BaseDTOs
{
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public string $remember_token;

    /**
     * Construct the DTO with the input request.
     */
    public function __construct(mixed $request)
    {
        $this->name = $request['name'];
        $this->email = $request['email'];
        $this->password = Hash::make($request['password'] ?? Str::random(10));
        $this->role = $request['role'];
        $this->remember_token = Str::random(60);
    }
}
