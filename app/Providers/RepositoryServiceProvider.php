<?php

namespace App\Providers;

use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\MobileVerification\MobileVerificationRepository;
use App\Repositories\MobileVerification\MobileVerificationRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\Register\RegisterService;
use App\Services\Auth\Register\RegisterServiceInterface;
use App\Services\Cart\CartService;
use App\Services\Cart\CartServiceInterface;
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

        $this->app->bind(RegisterServiceInterface::class,RegisterService::class);

        /** Cart */
        $this->app->bind(CartRepositoryInterface::class,CartRepository::class);
        $this->app->bind(CartServiceInterface::class,CartService::class);
        $this->app->bind(CartItemRepositoryInterface::class,CartItemRepository::class);

        /** MobileVerification */
        $this->app->bind(MobileVerificationRepositoryInterface::class,MobileVerificationRepository::class);

        /** Sms */
        $this->app->bind(SmsServiceInterface::class,Sms::class);

    }

    public function boot()
    {
    }
}
