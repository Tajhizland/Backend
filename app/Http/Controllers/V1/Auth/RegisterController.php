<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\Register\RegisterRequest;
use App\Http\Requests\V1\Auth\Register\RegisterVerifyCodeRequest;
use App\Http\Requests\V1\Auth\Register\SendRegisterVerificationCodeRequest;
use App\Services\Auth\Register\RegisterServiceInterface;
use Illuminate\Support\Facades\Lang;

class RegisterController extends Controller
{
    public function __construct(private RegisterServiceInterface $registerService)
    {
    }

    public function sendVerificationCode(SendRegisterVerificationCodeRequest $request)
    {
        try {
            $this->registerService->sendVerificationCode($request->get("mobile"));
            return $this->successResponse(Lang::get("responses.verification_code_sent"));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function verifyCode(RegisterVerifyCodeRequest $request)
    {
        try {
            $this->registerService->verifyCode($request->get("mobile"), $request->get("code"));
            return $this->successResponse(Lang::get("responses.verification_code_verified"));
        } catch (\Exception $exception) {
            return $this->badRequestResponse($exception->getMessage());
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $token = $this->registerService->register($request->get("mobile"), $request->get("password"));
            return $this->dataResponse(
                ["token" => $token],
                Lang::get("responses.registration_success")
            );
        } catch (\Exception $exception) {
            return $this->badRequestResponse($exception->getMessage());
        }
    }
}
