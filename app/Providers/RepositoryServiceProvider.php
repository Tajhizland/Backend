<?php

namespace App\Providers;

use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Favorite\FavoriteRepository;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use App\Repositories\Filter\FilterRepository;
use App\Repositories\Filter\FilterRepositoryInterface;
use App\Repositories\FilterItem\FilterItemRepository;
use App\Repositories\FilterItem\FilterItemRepositoryInterface;
use App\Repositories\MobileVerification\MobileVerificationRepository;
use App\Repositories\MobileVerification\MobileVerificationRepositoryInterface;
use App\Repositories\New\NewRepository;
use App\Repositories\New\NewRepositoryInterface;
use App\Repositories\Option\OptionRepository;
use App\Repositories\Option\OptionRepositoryInterface;
use App\Repositories\OptionItem\OptionItemRepository;
use App\Repositories\OptionItem\OptionItemRepositoryInterface;
use App\Repositories\Price\PriceRepository;
use App\Repositories\Price\PriceRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use App\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use App\Repositories\ProductColor\ProductColorRepository;
use App\Repositories\ProductColor\ProductColorRepositoryInterface;
use App\Repositories\ResetPassword\ResetPasswordRepository;
use App\Repositories\ResetPassword\ResetPasswordRepositoryInterface;
use App\Repositories\Stock\StockRepository;
use App\Repositories\Stock\StockRepositoryInterface;
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
use App\Services\Category\CategoryService;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Favorite\FavoriteService;
use App\Services\Favorite\FavoriteServiceInterface;
use App\Services\Filter\FilterService;
use App\Services\Filter\FilterServiceInterface;
use App\Services\New\NewService;
use App\Services\New\NewServiceInterface;
use App\Services\Option\OptionService;
use App\Services\Option\OptionServiceInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use App\Services\Search\SearchService;
use App\Services\Search\SearchServiceInterface;
use App\Services\Sms\Sms;
use App\Services\Sms\SmsServiceInterface;
use App\Services\User\UserService;
use App\Services\User\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        /** Repository */

        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);

        $this->app->bind(CartItemRepositoryInterface::class, CartItemRepository::class);

        $this->app->bind(MobileVerificationRepositoryInterface::class, MobileVerificationRepository::class);

        $this->app->bind(ResetPasswordRepositoryInterface::class, ResetPasswordRepository::class);

        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(ProductColorRepositoryInterface::class, ProductColorRepository::class);

        $this->app->bind(StockRepositoryInterface::class, StockRepository::class);

        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);

        $this->app->bind(NewRepositoryInterface::class, NewRepository::class);

        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);

        $this->app->bind(FilterRepositoryInterface::class, FilterRepository::class);

        $this->app->bind(FilterItemRepositoryInterface::class, FilterItemRepository::class);

        $this->app->bind(OptionRepositoryInterface::class, OptionRepository::class);

        $this->app->bind(OptionItemRepositoryInterface::class, OptionItemRepository::class);


        /** End Repository */

        /** ****************************************************************************************   */


        /** Service */

        $this->app->bind(UserServiceInterface::class, UserService::class);

        $this->app->bind(RegisterServiceInterface::class, RegisterService::class);

        $this->app->bind(LoginServiceInterface::class, LoginService::class);

        $this->app->bind(ResetPasswordServiceInterface::class, ResetPasswordService::class);

        $this->app->bind(CartServiceInterface::class, CartService::class);

        $this->app->bind(SmsServiceInterface::class, Sms::class);

        $this->app->bind(ProductServiceInterface::class, ProductService::class);

        $this->app->bind(PriceRepositoryInterface::class, PriceRepository::class);

        $this->app->bind(SearchServiceInterface::class, SearchService::class);

        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);

        $this->app->bind(FilterServiceInterface::class, FilterService::class);

        $this->app->bind(FavoriteServiceInterface::class, FavoriteService::class);

        $this->app->bind(NewServiceInterface::class, NewService::class);

        $this->app->bind(OptionServiceInterface::class, OptionService::class);



        /** End Service */

    }

    public function boot()
    {
    }
}
