<?php

namespace App\Http\Controllers\V1\Auth;

use App\DTOs\Auth\MobileDto;
use App\DTOs\Auth\ResetPasswordDto;
use App\DTOs\Auth\VerifyCodeDto;
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
        $this->resetPasswordService->sendVerificationCode((new MobileDto(...$request->validated()))->mobile);
        return $this->successResponse(__("action.send",["attr"=>__("attr.verify_code")]));
    }

    public function verifyCode(ResetPasswordVerifyCodeRequest $request)
    {
        $dto = new VerifyCodeDto(...$request->validated());
        $this->resetPasswordService->verifyCode($dto->mobile, $dto->code);
        return $this->successResponse(__("action.verify",["attr"=>__("attr.verify_code")]));
    }

    public function reset(ResetPasswordRequest $request)
    {
        $dto = new ResetPasswordDto(...$request->validated());
        $token = $this->resetPasswordService->reset($dto->mobile, $dto->password);
        return $this->dataResponse(
            ["token" => $token],
            __("action.change",["attr"=>__("attr.password")])
        );
    }
}
