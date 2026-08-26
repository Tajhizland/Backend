<?php

namespace App\Http\Controllers\V1\Auth;

use App\DTOs\Auth\LoginDto;
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
        $dto = new LoginDto(...$request->validated());
        $token = $this->loginService->login(["username" => $dto->username, "password" => $dto->password]);
        return $this->dataResponse
        (
            ["token" => $token],
            (__("action.success",["attr"=>__("attr.login")]))
        );
    }
}
