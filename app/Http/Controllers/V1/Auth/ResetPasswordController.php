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
        $this->resetPasswordService->sendVerificationCode($request->get("mobile"));
        return $this->successResponse(Lang::get("action.send",["attr"=>Lang::get("attr.verify_code")]));
    }

    public function verifyCode(ResetPasswordVerifyCodeRequest $request)
    {
        $this->resetPasswordService->verifyCode($request->get("mobile"), $request->get("code"));
        return $this->successResponse(Lang::get("action.verify",["attr"=>Lang::get("attr.verify_code")]));
    }

    public function reset(ResetPasswordRequest $request)
    {
        $token = $this->resetPasswordService->reset($request->get("mobile"), $request->get("password"));
        return $this->dataResponse(
            ["token" => $token],
            Lang::get("action.change",["attr"=>Lang::get("attr.password")])
        );
    }
}
