<?php

namespace App\Http\Controllers\V1\Auth;

use App\DTOs\Auth\MobileDto;
use App\DTOs\Auth\RegisterDto;
use App\DTOs\Auth\VerifyCodeDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Register\RegisterRequest;
use App\Http\Requests\Auth\Register\RegisterVerifyCodeRequest;
use App\Http\Requests\Auth\Register\SendRegisterVerificationCodeRequest;
use App\Services\Auth\Register\RegisterServiceInterface;

class RegisterController extends Controller
{
    public function __construct(private readonly RegisterServiceInterface $registerService)
    {
    }

    public function sendVerificationCode(SendRegisterVerificationCodeRequest $request)
    {
        $dto = new MobileDto(...$request->validated());
        $this->registerService->sendVerificationCode($dto->mobile);
        return $this->successResponse(__("action.send", ["attr" => __("attr.verify_code"), "to" => $dto->mobile]));

    }

    public function verifyCode(RegisterVerifyCodeRequest $request)
    {
        $dto = new VerifyCodeDto(...$request->validated());
        $this->registerService->verifyCode($dto->mobile, $dto->code);
        return $this->successResponse(__("action.verify",["attr"=>__("attr.verify_code")]));
    }

    public function register(RegisterRequest $request)
    {
        $dto = new RegisterDto(...$request->validated());
        $token = $this->registerService->register($dto->mobile, $dto->password, $dto->name, $dto->last_name, $dto->national_code);
        return $this->dataResponse(
            ["token" => $token],
            __("action.success",["attr"=>__("attr.register")])
        );

    }
}
