<?php

namespace App\Services\Checkout\States;

use App\Exceptions\BreakException;
use App\Models\Cart;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Lang;

class UserInfoCheckHandler implements CheckoutHandlerInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    private ?CheckoutHandlerInterface $nextHandler = null;

    public function setNext(CheckoutHandlerInterface $handler): void
    {
        $this->nextHandler = $handler;
    }

    public function handle(Cart $cart,   $cartItem)
    {
        $user = $this->userRepository->findOrFail($cart->user_id);
        if (!$user) {
            throw  new BreakException(Lang::get("کاربر یافت نشد"));
        }
        if (!$user->name || $user->name=="") {
            throw  new BreakException(Lang::get("پر کردن نام الزامی است"));
        }
        if (!$user->last_name || $user->last_name=="") {
            throw  new BreakException(Lang::get("پر کردن نام خانوادگی الزامی است"));
        }
        if (!$user->national_code || $user->national_code=="") {
            throw  new BreakException(Lang::get("پر کردن کد ملی الزامی است"));
        }
        if ($this->nextHandler) {
            return $this->nextHandler->handle($cart,  $cartItem);
        }
        return true;
    }
}
