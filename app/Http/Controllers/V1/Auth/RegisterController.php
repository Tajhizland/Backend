<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\Register\RegisterRequest;
use App\Http\Requests\V1\Auth\Register\RegisterVerifyCodeRequest;
use App\Http\Requests\V1\Auth\Register\SendRegisterVerificationCodeRequest;
use App\Services\Auth\Register\RegisterServiceInterface;

class RegisterController extends Controller
{
    public function __construct(private RegisterServiceInterface $registerService)
    {
    }

    public function sendVerificationCode(SendRegisterVerificationCodeRequest $request)
    {
        try {
            $this->registerService->sendVerificationCode($request->get("mobile"));
            return  $this->successResponse( "کد تأیید به شماره همراه ارسال شد ." );
        }

        catch (\Exception $exception)
        {
            return $this->errorResponse($exception->getMessage());
        }
    }
    public function verifyCode(RegisterVerifyCodeRequest $request)
    {
        try {
            $this->registerService->verifyCode($request->get("mobile") ,$request->get("code"));
            return  $this->successResponse(
                ['message' => 'کد تأیید با موفقیت تأیید شد .']
            );
        }
        catch (\Exception $exception)
        {
            return $this->badRequestResponse($exception->getMessage());
        }
    }
    public function register(RegisterRequest $request)
    {
        try {
            $this->registerService->register($request->get("mobile") ,$request->get("password"));
            return  $this->successResponse(
                ['message' => 'ثبت نام با موفقیت انجام شد .']
            );
        }
        catch (\Exception $exception)
        {
            return $this->badRequestResponse($exception->getMessage());
        }
    }
}
