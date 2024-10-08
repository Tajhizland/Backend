<?php

namespace App\Providers;

use App\Repositories\Address\AddressRepository;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\CategoryConcept\CategoryConceptRepository;
use App\Repositories\CategoryConcept\CategoryConceptRepositoryInterface;
use App\Repositories\City\CityRepository;
use App\Repositories\City\CityRepositoryInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Concept\ConceptRepository;
use App\Repositories\Concept\ConceptRepositoryInterface;
use App\Repositories\Delivery\DeliveryRepository;
use App\Repositories\Delivery\DeliveryRepositoryInterface;
use App\Repositories\Favorite\FavoriteRepository;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use App\Repositories\FileManager\FileManagerRepository;
use App\Repositories\FileManager\FileManagerRepositoryInterface;
use App\Repositories\Filter\FilterRepository;
use App\Repositories\Filter\FilterRepositoryInterface;
use App\Repositories\FilterItem\FilterItemRepository;
use App\Repositories\FilterItem\FilterItemRepositoryInterface;
use App\Repositories\Gateway\GatewayRepository;
use App\Repositories\Gateway\GatewayRepositoryInterface;
use App\Repositories\HomepageCategory\HomepageCategoryRepository;
use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;
use App\Repositories\Menu\MenuRepository;
use App\Repositories\Menu\MenuRepositoryInterface;
use App\Repositories\MobileVerification\MobileVerificationRepository;
use App\Repositories\MobileVerification\MobileVerificationRepositoryInterface;
use App\Repositories\New\NewRepository;
use App\Repositories\New\NewRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\OnHoldOrder\OnHoldOrderRepository;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Option\OptionRepository;
use App\Repositories\Option\OptionRepositoryInterface;
use App\Repositories\OptionItem\OptionItemRepository;
use App\Repositories\OptionItem\OptionItemRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderInfo\OrderInfoRepository;
use App\Repositories\OrderInfo\OrderInfoRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepository;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\PopularCategory\PopularCategoryRepository;
use App\Repositories\PopularCategory\PopularCategoryRepositoryInterface;
use App\Repositories\PopularProduct\PopularProductRepository;
use App\Repositories\PopularProduct\PopularProductRepositoryInterface;
use App\Repositories\Price\PriceRepository;
use App\Repositories\Price\PriceRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use App\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use App\Repositories\ProductColor\ProductColorRepository;
use App\Repositories\ProductColor\ProductColorRepositoryInterface;
use App\Repositories\ProductFilter\ProductFilterRepository;
use App\Repositories\ProductFilter\ProductFilterRepositoryInterface;
use App\Repositories\ProductImage\ProductImageRepository;
use App\Repositories\ProductImage\ProductImageRepositoryInterface;
use App\Repositories\ProductOption\ProductOptionRepository;
use App\Repositories\ProductOption\ProductOptionRepositoryInterface;
use App\Repositories\Province\ProvinceRepository;
use App\Repositories\Province\ProvinceRepositoryInterface;
use App\Repositories\ResetPassword\ResetPasswordRepository;
use App\Repositories\ResetPassword\ResetPasswordRepositoryInterface;
use App\Repositories\Returned\ReturnedRepository;
use App\Repositories\Returned\ReturnedRepositoryInterface;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\Repositories\Slider\SliderRepository;
use App\Repositories\Slider\SliderRepositoryInterface;
use App\Repositories\SpecialProduct\SpecialProductRepository;
use App\Repositories\SpecialProduct\SpecialProductRepositoryInterface;
use App\Repositories\Stock\StockRepository;
use App\Repositories\Stock\StockRepositoryInterface;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Address\AddressService;
use App\Services\Address\AddressServiceInterface;
use App\Services\Auth\Login\LoginService;
use App\Services\Auth\Login\LoginServiceInterface;
use App\Services\Auth\Register\RegisterService;
use App\Services\Auth\Register\RegisterServiceInterface;
use App\Services\Auth\ResetPassword\ResetPasswordService;
use App\Services\Auth\ResetPassword\ResetPasswordServiceInterface;
use App\Services\Brand\BrandService;
use App\Services\Brand\BrandServiceInterface;
use App\Services\Cart\CartService;
use App\Services\Cart\CartServiceInterface;
use App\Services\CartItem\CartItemService;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\Category\CategoryService;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Checkout\CheckoutService;
use App\Services\Checkout\CheckoutServiceInterface;
use App\Services\Comment\CommentService;
use App\Services\Comment\CommentServiceInterface;
use App\Services\Concept\ConceptService;
use App\Services\Concept\ConceptServiceInterface;
use App\Services\Delivery\DeliveryService;
use App\Services\Delivery\DeliveryServiceInterface;
use App\Services\Favorite\FavoriteService;
use App\Services\Favorite\FavoriteServiceInterface;
use App\Services\FileManager\FileManagerService;
use App\Services\FileManager\FileManagerServiceInterface;
use App\Services\Filter\FilterService;
use App\Services\Filter\FilterServiceInterface;
use App\Services\Gateway\GatewayService;
use App\Services\Gateway\GatewayServiceInterface;
use App\Services\Helper\CartHelper\CartHelperService;
use App\Services\Helper\CartHelper\CartHelperServiceInterface;
use App\Services\HomePage\HomePageService;
use App\Services\HomePage\HomePageServiceInterface;
use App\Services\HomepageCategory\HomepageCategoryService;
use App\Services\HomepageCategory\HomepageCategoryServiceInterface;
use App\Services\Menu\MenuService;
use App\Services\Menu\MenuServiceInterface;
use App\Services\New\NewService;
use App\Services\New\NewServiceInterface;
use App\Services\Notification\NotificationService;
use App\Services\Notification\NotificationServiceInterface;
use App\Services\OnHoldOrder\OnHoldOrderService;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use App\Services\Option\OptionService;
use App\Services\Option\OptionServiceInterface;
use App\Services\Order\OrderService;
use App\Services\Order\OrderServiceInterface;
use App\Services\Payment\Gateways\Strategy\GatewayStrategyServices;
use App\Services\Payment\Gateways\Strategy\GatewayStrategyServicesInterface;
use App\Services\Payment\PaymentService;
use App\Services\Payment\PaymentServicesInterface;
use App\Services\PopularCategory\PopularCategoryService;
use App\Services\PopularCategory\PopularCategoryServiceInterface;
use App\Services\PopularProduct\PopularProductService;
use App\Services\PopularProduct\PopularProductServiceInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductColor\ProductColorService;
use App\Services\ProductColor\ProductColorServiceInterface;
use App\Services\ProductImage\ProductImageService;
use App\Services\ProductImage\ProductImageServiceInterface;
use App\Services\Returned\ReturnedService;
use App\Services\Returned\ReturnedServiceInterface;
use App\Services\S3\S3Service;
use App\Services\S3\S3ServiceInterface;
use App\Services\Search\SearchService;
use App\Services\Search\SearchServiceInterface;
use App\Services\Setting\SettingService;
use App\Services\Setting\SettingServiceInterface;
use App\Services\Slider\SliderService;
use App\Services\Slider\SliderServiceInterface;
use App\Services\Sms\SmsService;
use App\Services\Sms\SmsServiceInterface;
use App\Services\SpecialProduct\SpecialProductService;
use App\Services\SpecialProduct\SpecialProductServiceInterface;
use App\Services\User\UserService;
use App\Services\User\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        /** Repository */

        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);

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

        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);

        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);

        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);

        $this->app->bind(DeliveryRepositoryInterface::class, DeliveryRepository::class);

        $this->app->bind(GatewayRepositoryInterface::class, GatewayRepository::class);

        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);

        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);

        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);

        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

        $this->app->bind(OnHoldOrderRepositoryInterface::class, OnHoldOrderRepository::class);

        $this->app->bind(OrderItemRepositoryInterface::class, OrderItemRepository::class);

        $this->app->bind(ReturnedRepositoryInterface::class, ReturnedRepository::class);

        $this->app->bind(OrderInfoRepositoryInterface::class, OrderInfoRepository::class);

        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);

        $this->app->bind(ProductImageRepositoryInterface::class, ProductImageRepository::class);

        $this->app->bind(FileManagerRepositoryInterface::class, FileManagerRepository::class);

        $this->app->bind(ProductFilterRepositoryInterface::class, ProductFilterRepository::class);

        $this->app->bind(ProductOptionRepositoryInterface::class, ProductOptionRepository::class);

        $this->app->bind(SliderRepositoryInterface::class, SliderRepository::class);

        $this->app->bind(PopularProductRepositoryInterface::class, PopularProductRepository::class);

        $this->app->bind(PopularCategoryRepositoryInterface::class, PopularCategoryRepository::class);

        $this->app->bind(SpecialProductRepositoryInterface::class, SpecialProductRepository::class);

        $this->app->bind(HomepageCategoryRepositoryInterface::class, HomepageCategoryRepository::class);

        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);

        $this->app->bind(ConceptRepositoryInterface::class, ConceptRepository::class);

        $this->app->bind(CategoryConceptRepositoryInterface::class, CategoryConceptRepository::class);


        /** End Repository */

        /** ****************************************************************************************   */


        /** Service */

        $this->app->bind(UserServiceInterface::class, UserService::class);

        $this->app->bind(RegisterServiceInterface::class, RegisterService::class);

        $this->app->bind(LoginServiceInterface::class, LoginService::class);

        $this->app->bind(ResetPasswordServiceInterface::class, ResetPasswordService::class);

        $this->app->bind(CartServiceInterface::class, CartService::class);

        $this->app->bind(SmsServiceInterface::class, SmsService::class);

        $this->app->bind(ProductServiceInterface::class, ProductService::class);

        $this->app->bind(PriceRepositoryInterface::class, PriceRepository::class);

        $this->app->bind(SearchServiceInterface::class, SearchService::class);

        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);

        $this->app->bind(FilterServiceInterface::class, FilterService::class);

        $this->app->bind(FavoriteServiceInterface::class, FavoriteService::class);

        $this->app->bind(NewServiceInterface::class, NewService::class);

        $this->app->bind(OptionServiceInterface::class, OptionService::class);

        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);

        $this->app->bind(BrandServiceInterface::class, BrandService::class);

        $this->app->bind(HomePageServiceInterface::class, HomePageService::class);

        $this->app->bind(SettingServiceInterface::class, SettingService::class);

        $this->app->bind(DeliveryServiceInterface::class, DeliveryService::class);

        $this->app->bind(GatewayServiceInterface::class, GatewayService::class);

        $this->app->bind(AddressServiceInterface::class, AddressService::class);

        $this->app->bind(CommentServiceInterface::class, CommentService::class);

        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        $this->app->bind(OnHoldOrderServiceInterface::class, OnHoldOrderService::class);

        $this->app->bind(ReturnedServiceInterface::class, ReturnedService::class);

        $this->app->bind(CartItemServiceInterface::class, CartItemService::class);

        $this->app->bind(PaymentServicesInterface::class, PaymentService::class);

        $this->app->bind(GatewayStrategyServicesInterface::class, GatewayStrategyServices::class);

        $this->app->bind(S3ServiceInterface::class, S3Service::class);

        $this->app->bind(ProductColorServiceInterface::class, ProductColorService::class);

        $this->app->bind(ProductImageServiceInterface::class, ProductImageService::class);

        $this->app->bind(FileManagerServiceInterface::class, FileManagerService::class);

        $this->app->bind(SliderServiceInterface::class, SliderService::class);

        $this->app->bind(PopularProductServiceInterface::class, PopularProductService::class);

        $this->app->bind(PopularCategoryServiceInterface::class, PopularCategoryService::class);

        $this->app->bind(SpecialProductServiceInterface::class, SpecialProductService::class);

        $this->app->bind(HomepageCategoryServiceInterface::class, HomepageCategoryService::class);

        $this->app->bind(MenuServiceInterface::class, MenuService::class);

        $this->app->bind(ConceptServiceInterface::class, ConceptService::class);

        $this->app->bind(CheckoutServiceInterface::class, CheckoutService::class);


        /** End Service */

    }

    public function boot()
    {
    }
}
