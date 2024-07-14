<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ResetPassword\ResetPasswordRequest;
use App\Http\Requests\V1\Auth\ResetPassword\ResetPasswordVerifyCodeRequest;
use App\Http\Requests\V1\Auth\ResetPassword\SendResetPasswordVerificationCodeRequest;
use App\Services\Auth\ResetPassword\ResetPasswordServiceInterface;
use Illuminate\Support\Facades\Lang;

class ResetPasswordController extends Controller
{
    public function __construct(private ResetPasswordServiceInterface $resetPasswordService)
    {
    }

    public function sendVerificationCode(SendResetPasswordVerificationCodeRequest $request)
    {
        try {
            $this->resetPasswordService->sendVerificationCode($request->get("mobile"));
            return $this->successResponse(Lang::get("responses.verification_code_sent"));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function verifyCode(ResetPasswordVerifyCodeRequest $request)
    {
        try {
            $this->resetPasswordService->verifyCode($request->get("mobile"), $request->get("code"));
            return $this->successResponse(Lang::get("responses.verification_code_verified"));
        } catch (\Exception $exception) {
            return $this->badRequestResponse($exception->getMessage());
        }
    }

    public function reset(ResetPasswordRequest $request)
    {
        try {
            $token=$this->resetPasswordService->reset($request->get("mobile"), $request->get("password"));
            return $this->dataResponse(
                ["token"=>$token],
                Lang::get("responses.reset_password_success")
            );
        } catch (\Exception $exception) {
            return $this->badRequestResponse($exception->getMessage());
        }
    }
}
