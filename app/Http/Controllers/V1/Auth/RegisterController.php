<?php

namespace App\Http\Controllers\V1\Auth;

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
        $this->registerService->sendVerificationCode($request->get("mobile"));
        return $this->successResponse(__("action.send",["attr"=>__("attr.verify_code") , "to"=>$request->get("mobile")]));

    }

    public function verifyCode(RegisterVerifyCodeRequest $request)
    {
        $this->registerService->verifyCode($request->get("mobile"), $request->get("code"));
        return $this->successResponse(__("action.verify",["attr"=>__("attr.verify_code")]));
    }

    public function register(RegisterRequest $request)
    {
        $token = $this->registerService->register($request->get("mobile"), $request->get("password"), $request->get("name"), $request->get("last_name"), $request->get("national_code"));
        return $this->dataResponse(
            ["token" => $token],
            __("action.success",["attr"=>__("attr.register")])
        );

    }
}
