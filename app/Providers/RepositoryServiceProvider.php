<?php

namespace App\Providers;

use App\Repositories\Address\AddressRepository;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\Banner\BannerRepository;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\BaseRepositoryInterface;
use App\Repositories\BlogCategory\BlogCategoryRepository;
use App\Repositories\BlogCategory\BlogCategoryRepositoryInterface;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Campaign\CampaignRepository;
use App\Repositories\Campaign\CampaignRepositoryInterface;
use App\Repositories\CampaignSlider\CampaignSliderRepository;
use App\Repositories\CampaignSlider\CampaignSliderRepositoryInterface;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Cast\CastRepository;
use App\Repositories\Cast\CastRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\CategoryConcept\CategoryConceptRepository;
use App\Repositories\CategoryConcept\CategoryConceptRepositoryInterface;
use App\Repositories\CategoryViewHistory\CategoryViewHistoryRepository;
use App\Repositories\CategoryViewHistory\CategoryViewHistoryRepositoryInterface;
use App\Repositories\ChatInfo\ChatInfoRepository;
use App\Repositories\ChatInfo\ChatInfoRepositoryInterface;
use App\Repositories\City\CityRepository;
use App\Repositories\City\CityRepositoryInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Concept\ConceptRepository;
use App\Repositories\Concept\ConceptRepositoryInterface;
use App\Repositories\Contact\ContactRepository;
use App\Repositories\Contact\ContactRepositoryInterface;
use App\Repositories\Delivery\DeliveryRepository;
use App\Repositories\Delivery\DeliveryRepositoryInterface;
use App\Repositories\Dictionary\DictionaryRepository;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use App\Repositories\Faq\FaqRepository;
use App\Repositories\Faq\FaqRepositoryInterface;
use App\Repositories\Favorite\FavoriteRepository;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use App\Repositories\FileManager\FileManagerRepository;
use App\Repositories\FileManager\FileManagerRepositoryInterface;
use App\Repositories\Filter\FilterRepository;
use App\Repositories\Filter\FilterRepositoryInterface;
use App\Repositories\FilterItem\FilterItemRepository;
use App\Repositories\FilterItem\FilterItemRepositoryInterface;
use App\Repositories\Footprint\FootprintRepository;
use App\Repositories\Footprint\FootprintRepositoryInterface;
use App\Repositories\Gateway\GatewayRepository;
use App\Repositories\Gateway\GatewayRepositoryInterface;
use App\Repositories\GroupField\GroupFieldRepository;
use App\Repositories\GroupField\GroupFieldRepositoryInterface;
use App\Repositories\GroupFieldValue\GroupFieldValueRepository;
use App\Repositories\GroupFieldValue\GroupFieldValueRepositoryInterface;
use App\Repositories\GroupProduct\GroupProductRepository;
use App\Repositories\GroupProduct\GroupProductRepositoryInterface;
use App\Repositories\Guaranty\GuarantyRepository;
use App\Repositories\Guaranty\GuarantyRepositoryInterface;
use App\Repositories\HomepageCategory\HomepageCategoryRepository;
use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;
use App\Repositories\HomepageVlog\HomepageVlogRepository;
use App\Repositories\HomepageVlog\HomepageVlogRepositoryInterface;
use App\Repositories\Landing\LandingRepository;
use App\Repositories\Landing\LandingRepositoryInterface;
use App\Repositories\LandingBanner\LandingBannerRepository;
use App\Repositories\LandingBanner\LandingBannerRepositoryInterface;
use App\Repositories\LandingCategory\LandingCategoryRepository;
use App\Repositories\LandingCategory\LandingCategoryRepositoryInterface;
use App\Repositories\LandingProduct\LandingProductRepository;
use App\Repositories\LandingProduct\LandingProductRepositoryInterface;
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
use App\Repositories\Page\PageRepository;
use App\Repositories\Page\PageRepositoryInterface;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\PhoneBock\PhoneBockRepository;
use App\Repositories\PhoneBock\PhoneBockRepositoryInterface;
use App\Repositories\PopularCategory\PopularCategoryRepository;
use App\Repositories\PopularCategory\PopularCategoryRepositoryInterface;
use App\Repositories\PopularProduct\PopularProductRepository;
use App\Repositories\PopularProduct\PopularProductRepositoryInterface;
use App\Repositories\Poster\PosterRepository;
use App\Repositories\Poster\PosterRepositoryInterface;
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
use App\Repositories\ProductGuaranty\ProductGuarantyRepository;
use App\Repositories\ProductGuaranty\ProductGuarantyRepositoryInterface;
use App\Repositories\ProductImage\ProductImageRepository;
use App\Repositories\ProductImage\ProductImageRepositoryInterface;
use App\Repositories\ProductOption\ProductOptionRepository;
use App\Repositories\ProductOption\ProductOptionRepositoryInterface;
use App\Repositories\ProductVideo\ProductVideoRepository;
use App\Repositories\ProductVideo\ProductVideoRepositoryInterface;
use App\Repositories\Province\ProvinceRepository;
use App\Repositories\Province\ProvinceRepositoryInterface;
use App\Repositories\ResetPassword\ResetPasswordRepository;
use App\Repositories\ResetPassword\ResetPasswordRepositoryInterface;
use App\Repositories\Returned\ReturnedRepository;
use App\Repositories\Returned\ReturnedRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\RolePermission\RolePermissionRepository;
use App\Repositories\RolePermission\RolePermissionRepositoryInterface;
use App\Repositories\RunConceptAnswer\RunConceptAnswerRepository;
use App\Repositories\RunConceptAnswer\RunConceptAnswerRepositoryInterface;
use App\Repositories\RunConceptQuestion\RunConceptQuestionRepository;
use App\Repositories\RunConceptQuestion\RunConceptQuestionRepositoryInterface;
use App\Repositories\Sample\SampleRepository;
use App\Repositories\Sample\SampleRepositoryInterface;
use App\Repositories\SampleImage\SampleImageRepository;
use App\Repositories\SampleImage\SampleImageRepositoryInterface;
use App\Repositories\SampleVideo\SampleVideoRepository;
use App\Repositories\SampleVideo\SampleVideoRepositoryInterface;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\Repositories\Slider\SliderRepository;
use App\Repositories\Slider\SliderRepositoryInterface;
use App\Repositories\SmsLog\SmsLogRepository;
use App\Repositories\SmsLog\SmsLogRepositoryInterface;
use App\Repositories\SmsLogItem\SmsLogItemRepository;
use App\Repositories\SmsLogItem\SmsLogItemRepositoryInterface;
use App\Repositories\SpecialProduct\SpecialProductRepository;
use App\Repositories\SpecialProduct\SpecialProductRepositoryInterface;
use App\Repositories\Stock\StockRepository;
use App\Repositories\Stock\StockRepositoryInterface;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\TrustedBrand\TrustedBrandRepository;
use App\Repositories\TrustedBrand\TrustedBrandRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Vlog\VlogRepository;
use App\Repositories\Vlog\VlogRepositoryInterface;
use App\Repositories\VlogCategory\VlogCategoryRepository;
use App\Repositories\VlogCategory\VlogCategoryRepositoryInterface;
use App\Repositories\WalletTransaction\WalletTransactionRepository;
use App\Repositories\WalletTransaction\WalletTransactionRepositoryInterface;
use App\Services\Address\AddressService;
use App\Services\Address\AddressServiceInterface;
use App\Services\Auth\Login\LoginService;
use App\Services\Auth\Login\LoginServiceInterface;
use App\Services\Auth\Register\RegisterService;
use App\Services\Auth\Register\RegisterServiceInterface;
use App\Services\Auth\ResetPassword\ResetPasswordService;
use App\Services\Auth\ResetPassword\ResetPasswordServiceInterface;
use App\Services\Banner\BannerService;
use App\Services\Banner\BannerServiceInterface;
use App\Services\BlogCategory\BlogCategoryService;
use App\Services\BlogCategory\BlogCategoryServiceInterface;
use App\Services\Brand\BrandService;
use App\Services\Brand\BrandServiceInterface;
use App\Services\Breadcrumb\BreadcrumbService;
use App\Services\Breadcrumb\BreadcrumbServiceInterface;
use App\Services\Campaign\CampaignService;
use App\Services\Campaign\CampaignServiceInterface;
use App\Services\CampaignSlider\CampaignSliderService;
use App\Services\CampaignSlider\CampaignSliderServiceInterface;
use App\Services\Cart\CartService;
use App\Services\Cart\CartServiceInterface;
use App\Services\CartItem\CartItemService;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\Cast\CastService;
use App\Services\Cast\CastServiceInterface;
use App\Services\Category\CategoryService;
use App\Services\Category\CategoryServiceInterface;
use App\Services\CategoryTree\CategoryTreeService;
use App\Services\CategoryTree\CategoryTreeServiceInterface;
use App\Services\CategoryViewHistory\CategoryViewHistoryService;
use App\Services\CategoryViewHistory\CategoryViewHistoryServiceInterface;
use App\Services\ChatInfo\ChatInfoService;
use App\Services\ChatInfo\ChatInfoServiceInterface;
use App\Services\Checkout\CheckoutService;
use App\Services\Checkout\CheckoutServiceInterface;
use App\Services\Comment\CommentService;
use App\Services\Comment\CommentServiceInterface;
use App\Services\Compare\CompareService;
use App\Services\Compare\CompareServiceInterface;
use App\Services\Concept\ConceptService;
use App\Services\Concept\ConceptServiceInterface;
use App\Services\Contact\ContactService;
use App\Services\Contact\ContactServiceInterface;
use App\Services\ConvertToHLS\HlsService;
use App\Services\ConvertToHLS\HlsServiceInterface;
use App\Services\Dashboard\DashboardService;
use App\Services\Dashboard\DashboardServiceInterface;
use App\Services\Delivery\DeliveryService;
use App\Services\Delivery\DeliveryServiceInterface;
use App\Services\Dictionary\DictionaryService;
use App\Services\Dictionary\DictionaryServiceInterface;
use App\Services\Faq\FaqService;
use App\Services\Faq\FaqServiceInterface;
use App\Services\Favorite\FavoriteService;
use App\Services\Favorite\FavoriteServiceInterface;
use App\Services\FileManager\FileManagerService;
use App\Services\FileManager\FileManagerServiceInterface;
use App\Services\Filter\FilterService;
use App\Services\Filter\FilterServiceInterface;
use App\Services\Gateway\GatewayService;
use App\Services\Gateway\GatewayServiceInterface;
use App\Services\Guaranty\GuarantyService;
use App\Services\Guaranty\GuarantyServiceInterface;
use App\Services\HomePage\HomePageService;
use App\Services\HomePage\HomePageServiceInterface;
use App\Services\HomepageCategory\HomepageCategoryService;
use App\Services\HomepageCategory\HomepageCategoryServiceInterface;
use App\Services\HomepageVlog\HomepageVlogService;
use App\Services\HomepageVlog\HomepageVlogServiceInterface;
use App\Services\ImageResize\ImageResizeService;
use App\Services\ImageResize\ImageResizeServiceInterface;
use App\Services\Landing\LandingService;
use App\Services\Landing\LandingServiceInterface;
use App\Services\Leading\LeadingService;
use App\Services\Leading\LeadingServiceInterface;
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
use App\Services\Page\PageService;
use App\Services\Page\PageServiceInterface;
use App\Services\Payment\Gateways\Strategy\GatewayStrategyServices;
use App\Services\Payment\Gateways\Strategy\GatewayStrategyServicesInterface;
use App\Services\Payment\PaymentService;
use App\Services\Payment\PaymentServicesInterface;
use App\Services\Permission\PermissionService;
use App\Services\Permission\PermissionServiceInterface;
use App\Services\PhoneBock\PhoneBockService;
use App\Services\PhoneBock\PhoneBockServiceInterface;
use App\Services\PopularCategory\PopularCategoryService;
use App\Services\PopularCategory\PopularCategoryServiceInterface;
use App\Services\PopularProduct\PopularProductService;
use App\Services\PopularProduct\PopularProductServiceInterface;
use App\Services\Poster\PosterService;
use App\Services\Poster\PosterServiceInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductCategory\ProductCategoryService;
use App\Services\ProductCategory\ProductCategoryServiceInterface;
use App\Services\ProductColor\ProductColorService;
use App\Services\ProductColor\ProductColorServiceInterface;
use App\Services\ProductGroup\ProductGroupService;
use App\Services\ProductGroup\ProductGroupServiceInterface;
use App\Services\ProductGuaranty\ProductGuarantyService;
use App\Services\ProductGuaranty\ProductGuarantyServiceInterface;
use App\Services\ProductImage\ProductImageService;
use App\Services\ProductImage\ProductImageServiceInterface;
use App\Services\Returned\ReturnedService;
use App\Services\Returned\ReturnedServiceInterface;
use App\Services\Role\RoleService;
use App\Services\Role\RoleServiceInterface;
use App\Services\RunConceptAnswer\RunConceptAnswerService;
use App\Services\RunConceptAnswer\RunConceptAnswerServiceInterface;
use App\Services\RunConceptQuestion\RunConceptQuestionService;
use App\Services\RunConceptQuestion\RunConceptQuestionServiceInterface;
use App\Services\S3\S3Service;
use App\Services\S3\S3ServiceInterface;
use App\Services\Sample\SampleService;
use App\Services\Sample\SampleServiceInterface;
use App\Services\Search\SearchService;
use App\Services\Search\SearchServiceInterface;
use App\Services\Setting\SettingService;
use App\Services\Setting\SettingServiceInterface;
use App\Services\Slider\SliderService;
use App\Services\Slider\SliderServiceInterface;
use App\Services\Sms\SmsService;
use App\Services\Sms\SmsServiceInterface;
use App\Services\SmsLog\SmsLogService;
use App\Services\SmsLog\SmsLogServiceInterface;
use App\Services\SmsLogItem\SmsLogItemService;
use App\Services\SmsLogItem\SmsLogItemServiceInterface;
use App\Services\SpecialProduct\SpecialProductService;
use App\Services\SpecialProduct\SpecialProductServiceInterface;
use App\Services\TrustedBrand\TrustedBrandService;
use App\Services\TrustedBrand\TrustedBrandServiceInterface;
use App\Services\User\UserService;
use App\Services\User\UserServiceInterface;
use App\Services\Vlog\VlogService;
use App\Services\Vlog\VlogServiceInterface;
use App\Services\VlogCategory\VlogCategoryService;
use App\Services\VlogCategory\VlogCategoryServiceInterface;
use App\Services\WalletTransaction\WalletTransactionService;
use App\Services\WalletTransaction\WalletTransactionServiceInterface;
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

        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);

        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);

        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);

        $this->app->bind(GuarantyRepositoryInterface::class, GuarantyRepository::class);

        $this->app->bind(BannerRepositoryInterface::class, BannerRepository::class);

        $this->app->bind(VlogRepositoryInterface::class, VlogRepository::class);

        $this->app->bind(VlogCategoryRepositoryInterface::class, VlogCategoryRepository::class);

        $this->app->bind(LandingRepositoryInterface::class, LandingRepository::class);

        $this->app->bind(LandingCategoryRepositoryInterface::class, LandingCategoryRepository::class);

        $this->app->bind(LandingProductRepositoryInterface::class, LandingProductRepository::class);

        $this->app->bind(LandingBannerRepositoryInterface::class, LandingBannerRepository::class);

        $this->app->bind(ProductGuarantyRepositoryInterface::class, ProductGuarantyRepository::class);

        $this->app->bind(PosterRepositoryInterface::class, PosterRepository::class);

        $this->app->bind(BlogCategoryRepositoryInterface::class, BlogCategoryRepository::class);

        $this->app->bind(SampleRepositoryInterface::class, SampleRepository::class);

        $this->app->bind(SampleImageRepositoryInterface::class, SampleImageRepository::class);

        $this->app->bind(SampleVideoRepositoryInterface::class, SampleVideoRepository::class);

        $this->app->bind(FootprintRepositoryInterface::class, FootprintRepository::class);

        $this->app->bind(ProductVideoRepositoryInterface::class, ProductVideoRepository::class);

        $this->app->bind(HomepageVlogRepositoryInterface::class, HomepageVlogRepository::class);

        $this->app->bind(TrustedBrandRepositoryInterface::class, TrustedBrandRepository::class);

        $this->app->bind(ChatInfoRepositoryInterface::class, ChatInfoRepository::class);

        $this->app->bind(GroupFieldRepositoryInterface::class, GroupFieldRepository::class);

        $this->app->bind(GroupFieldValueRepositoryInterface::class, GroupFieldValueRepository::class);

        $this->app->bind(GroupProductRepositoryInterface::class, GroupProductRepository::class);

        $this->app->bind(WalletTransactionRepositoryInterface::class, WalletTransactionRepository::class);

        $this->app->bind(CategoryViewHistoryRepositoryInterface::class, CategoryViewHistoryRepository::class);

        $this->app->bind(RunConceptAnswerRepositoryInterface::class, RunConceptAnswerRepository::class);

        $this->app->bind(RunConceptQuestionRepositoryInterface::class, RunConceptQuestionRepository::class);

        $this->app->bind(DictionaryRepositoryInterface::class, DictionaryRepository::class);

        $this->app->bind(SmsLogRepositoryInterface::class, SmsLogRepository::class);

        $this->app->bind(SmsLogItemRepositoryInterface::class, SmsLogItemRepository::class);

        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        $this->app->bind(RolePermissionRepositoryInterface::class, RolePermissionRepository::class);

        $this->app->bind(PhoneBockRepositoryInterface::class, PhoneBockRepository::class);

        $this->app->bind(CastRepositoryInterface::class, CastRepository::class);

        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);

        $this->app->bind(CampaignSliderRepositoryInterface::class, CampaignSliderRepository::class);


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

        $this->app->bind(ImageResizeServiceInterface::class, ImageResizeService::class);

        $this->app->bind(ContactServiceInterface::class, ContactService::class);

        $this->app->bind(FaqServiceInterface::class, FaqService::class);

        $this->app->bind(PageServiceInterface::class, PageService::class);

        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);

        $this->app->bind(GuarantyServiceInterface::class, GuarantyService::class);

        $this->app->bind(BannerServiceInterface::class, BannerService::class);

        $this->app->bind(VlogServiceInterface::class, VlogService::class);

        $this->app->bind(VlogCategoryServiceInterface::class, VlogCategoryService::class);

        $this->app->bind(LandingServiceInterface::class, LandingService::class);

        $this->app->bind(BreadcrumbServiceInterface::class, BreadcrumbService::class);

        $this->app->bind(ProductCategoryServiceInterface::class, ProductCategoryService::class);

        $this->app->bind(ProductGuarantyServiceInterface::class, ProductGuarantyService::class);

        $this->app->bind(PosterServiceInterface::class, PosterService::class);

        $this->app->bind(CategoryTreeServiceInterface::class, CategoryTreeService::class);

        $this->app->bind(BlogCategoryServiceInterface::class, BlogCategoryService::class);

        $this->app->bind(SampleServiceInterface::class, SampleService::class);

        $this->app->bind(HomepageVlogServiceInterface::class, HomepageVlogService::class);

        $this->app->bind(HlsServiceInterface::class, HlsService::class);

        $this->app->bind(TrustedBrandServiceInterface::class, TrustedBrandService::class);

        $this->app->bind(LeadingServiceInterface::class, LeadingService::class);

        $this->app->bind(ChatInfoServiceInterface::class, ChatInfoService::class);

        $this->app->bind(ProductGroupServiceInterface::class, ProductGroupService::class);

        $this->app->bind(CompareServiceInterface::class, CompareService::class);

        $this->app->bind(WalletTransactionServiceInterface::class, WalletTransactionService::class);

        $this->app->bind(CategoryViewHistoryServiceInterface::class, CategoryViewHistoryService::class);

        $this->app->bind(RunConceptAnswerServiceInterface::class, RunConceptAnswerService::class);

        $this->app->bind(RunConceptQuestionServiceInterface::class, RunConceptQuestionService::class);

        $this->app->bind(DictionaryServiceInterface::class, DictionaryService::class);

        $this->app->bind(SmsLogServiceInterface::class, SmsLogService::class);

        $this->app->bind(SmsLogItemServiceInterface::class, SmsLogItemService::class);

        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);

        $this->app->bind(RoleServiceInterface::class, RoleService::class);

        $this->app->bind(PhoneBockServiceInterface::class, PhoneBockService::class);

        $this->app->bind(CastServiceInterface::class, CastService::class);

        $this->app->bind(CampaignServiceInterface::class, CampaignService::class);

        $this->app->bind(CampaignSliderServiceInterface::class, CampaignSliderService::class);
        /** End Service */

    }

    public function boot()
    {
    }
}
