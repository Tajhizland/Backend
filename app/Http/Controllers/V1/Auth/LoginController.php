<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\LoginRequest;
use App\Services\Auth\Login\LoginServiceInterface;

class LoginController extends Controller
{
    public function __construct(private readonly LoginServiceInterface $loginService)
    {
    }

    public function login(LoginRequest $request)
    {
        $token = $this->loginService->login($request->validated());
        return $this->dataResponse
        (
            ["token" => $token],
            (__("action.success",["attr"=>__("attr.login")]))
        );
    }
}
