<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\CampaignBanner;
use App\Models\CampaignSlider;
use App\Models\Concept;
use App\Models\HomepageCategory;
use App\Models\HomepageVlog;
use App\Models\News;
use App\Models\Poster;
use App\Models\RandomProductCategory;
use App\Models\Slider;
use App\Models\SpecialProduct;
use App\Models\TrustedBrand;
use App\Models\Vlog;
use App\Services\HomePage\HomePageServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // migrate:fresh / migrate:refresh / db:wipe drop every table. The schema
        // baseline makes them look harmless, so block them outside local dev.
        DB::prohibitDestructiveCommands($this->app->isProduction());

        $this->invalidateHomePageCacheOnContentChange();
    }

    /**
     * مدل‌هایی که مستقیما محتوای صفحه اصلی را می‌سازند؛ با تغییرشان کش صفحه اصلی پاک می‌شود.
     *
     * دیتای محصول (قیمت/موجودی) عمدا اینجا نیست چون نرخ تغییرش بالاست؛
     * تازگی آن با settings.home_page.cache_ttl کنترل می‌شود.
     */
    private const HOME_PAGE_CONTENT_MODELS = [
        Banner::class,
        Slider::class,
        Poster::class,
        Concept::class,
        Brand::class,
        TrustedBrand::class,
        HomepageCategory::class,
        RandomProductCategory::class,
        HomepageVlog::class,
        SpecialProduct::class,
        Campaign::class,
        CampaignSlider::class,
        CampaignBanner::class,
        News::class,
        Vlog::class,
    ];

    private function invalidateHomePageCacheOnContentChange(): void
    {
        // flushCache دو کش دارد (پاسخ صفحه اصلی و فهرست کاندیدهای بخش منتخب)؛
        // اگر هر کدام روشن باشد هوک لازم است.
        $homePageTtl = (int) config("settings.home_page.cache_ttl");
        $candidateTtl = (int) config("settings.home_page.random_product_candidate_ttl");

        if ($homePageTtl <= 0 && $candidateTtl <= 0) {
            return;
        }

        $flush = fn () => $this->app->make(HomePageServiceInterface::class)->flushCache();

        foreach (self::HOME_PAGE_CONTENT_MODELS as $model) {
            /** @var class-string<Model> $model */
            $model::saved($flush);
            $model::deleted($flush);
        }
    }
}
