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
        $this->registerService->sendVerificationCode($request->get("mobile"));
        return $this->successResponse(Lang::get("action.send",["attr"=>Lang::get("attr.verify_code") , "to"=>$request->get("mobile")]));

    }

    public function verifyCode(RegisterVerifyCodeRequest $request)
    {
        $this->registerService->verifyCode($request->get("mobile"), $request->get("code"));
        return $this->successResponse(Lang::get("action.verify",["attr"=>Lang::get("attr.verify_code")]));
    }

    public function register(RegisterRequest $request)
    {
        $token = $this->registerService->register($request->get("mobile"), $request->get("password"), $request->get("name"), $request->get("last_name"), $request->get("national_code"));
        return $this->dataResponse(
            ["token" => $token],
            Lang::get("action.success",["attr"=>Lang::get("attr.register")])
        );

    }
}
