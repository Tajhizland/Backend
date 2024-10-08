<?php

namespace App\Services\Auth\Login;

use App\Exceptions\BreakException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class LoginService implements LoginServiceInterface
{
    public function login($credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;
            return $token;
        }
        throw new BreakException(Lang::get("exceptions.invalid_username_password"));
    }
}
