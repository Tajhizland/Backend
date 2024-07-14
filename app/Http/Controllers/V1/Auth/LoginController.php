<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\Login\LoginRequest;
use App\Services\Auth\Login\LoginService;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    public function __construct(private LoginService $loginService)
    {
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->loginService->login($request->validated());
            return $this->dataResponse
            (
                ["token" => $token],
                Lang::get("responses.login_success")
            );
        } catch (\Exception $exception) {
            return $this->badRequestResponse($exception->getMessage());
        }
    }
}
