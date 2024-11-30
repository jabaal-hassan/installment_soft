<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTOs;

class UserDTO extends BaseDTOs
{
    public $name;
    public $email;
    public $password;

    public function __construct($request)
    {
        $this->name = $request['name'];
        $this->email = $request['email'];
        $this->password = $request['password'];
    }
}
