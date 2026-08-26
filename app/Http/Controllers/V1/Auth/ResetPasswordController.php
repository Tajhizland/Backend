<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPassword\ResetPasswordRequest;
use App\Http\Requests\Auth\ResetPassword\ResetPasswordVerifyCodeRequest;
use App\Http\Requests\Auth\ResetPassword\SendResetPasswordVerificationCodeRequest;
use App\Services\Auth\ResetPassword\ResetPasswordServiceInterface;

class ResetPasswordController extends Controller
{
    public function __construct(private readonly ResetPasswordServiceInterface $resetPasswordService)
    {
    }

    public function sendVerificationCode(SendResetPasswordVerificationCodeRequest $request)
    {
        $this->resetPasswordService->sendVerificationCode($request->get("mobile"));
        return $this->successResponse(__("action.send",["attr"=>__("attr.verify_code")]));
    }

    public function verifyCode(ResetPasswordVerifyCodeRequest $request)
    {
        $this->resetPasswordService->verifyCode($request->get("mobile"), $request->get("code"));
        return $this->successResponse(__("action.verify",["attr"=>__("attr.verify_code")]));
    }

    public function reset(ResetPasswordRequest $request)
    {
        $token = $this->resetPasswordService->reset($request->get("mobile"), $request->get("password"));
        return $this->dataResponse(
            ["token" => $token],
            __("action.change",["attr"=>__("attr.password")])
        );
    }
}
