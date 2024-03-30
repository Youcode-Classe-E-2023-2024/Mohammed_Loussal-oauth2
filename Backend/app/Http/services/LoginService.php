<?php

namespace App\Http\services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginService
{

    static public function login($credentials, $remember)
    {
        return Auth::attempt($credentials, $remember);
    }

}
