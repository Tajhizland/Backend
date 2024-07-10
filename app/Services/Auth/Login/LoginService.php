<?php

namespace App\Services\Auth\Login;

use Illuminate\Support\Facades\Auth;

class LoginService implements  LoginServiceInterface
{
    public function login($credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;
            return $token;
        }
        return false;
    }
}
