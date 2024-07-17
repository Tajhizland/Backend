<?php

namespace App\Providers;

use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Invoice\InvoiceRepositoryInterface;
use App\Repositories\MobileVerification\MobileVerificationRepository;
use App\Repositories\MobileVerification\MobileVerificationRepositoryInterface;
use App\Repositories\Price\PriceRepository;
use App\Repositories\Price\PriceRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductColor\ProductColorRepository;
use App\Repositories\ProductColor\ProductColorRepositoryInterface;
use App\Repositories\ResetPassword\ResetPasswordRepository;
use App\Repositories\ResetPassword\ResetPasswordRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\Login\LoginService;
use App\Services\Auth\Login\LoginServiceInterface;
use App\Services\Auth\Register\RegisterService;
use App\Services\Auth\Register\RegisterServiceInterface;
use App\Services\Auth\ResetPassword\ResetPasswordService;
use App\Services\Auth\ResetPassword\ResetPasswordServiceInterface;
use App\Services\Cart\CartService;
use App\Services\Cart\CartServiceInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use App\Services\Sms\Sms;
use App\Services\Sms\SmsServiceInterface;
use App\Services\User\UserService;
use App\Services\User\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class,BaseRepository::class);
        /** User */
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
        $this->app->bind(UserServiceInterface::class,UserService::class);

        /** auth */

        $this->app->bind(RegisterServiceInterface::class,RegisterService::class);
        $this->app->bind(LoginServiceInterface::class,LoginService::class);
        $this->app->bind(ResetPasswordServiceInterface::class,ResetPasswordService::class);

        /** Cart */
        $this->app->bind(CartRepositoryInterface::class,CartRepository::class);
        $this->app->bind(CartServiceInterface::class,CartService::class);
        $this->app->bind(CartItemRepositoryInterface::class,CartItemRepository::class);

        /** MobileVerification */
        $this->app->bind(MobileVerificationRepositoryInterface::class,MobileVerificationRepository::class);

        /** ResetPassword */
        $this->app->bind(ResetPasswordRepositoryInterface::class,ResetPasswordRepository::class);

        /** Sms */
        $this->app->bind(SmsServiceInterface::class,Sms::class);

        /** Product */
        $this->app->bind(ProductRepositoryInterface::class,ProductRepository::class);
        $this->app->bind(ProductServiceInterface::class,ProductService::class);
        $this->app->bind(ProductColorRepositoryInterface::class,ProductColorRepository::class);
        $this->app->bind(PriceRepositoryInterface::class,PriceRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class,InvoiceRepository::class);

    }

    public function boot()
    {
    }
}
