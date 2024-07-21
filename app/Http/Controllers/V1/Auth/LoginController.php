<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\Login\LoginRequest;
use App\Services\Auth\Login\LoginServiceInterface;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    public function __construct(private LoginServiceInterface $loginService)
    {
    }

    public function login(LoginRequest $request)
    {
        $token = $this->loginService->login($request->validated());
        return $this->dataResponse
        (
            ["token" => $token],
            Lang::get("responses.login_success")
        );
    }
}
