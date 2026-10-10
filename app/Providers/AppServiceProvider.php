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
use App\Services\Seo\ShopCacheInvalidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ShopCacheInvalidator::class);
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
        $this->invalidateShopCacheOnContentChange();
    }

    private function invalidateShopCacheOnContentChange(): void
    {
        if (! config('services.shop_cache.url') || ! config('services.shop_cache.secret')) {
            return;
        }
        $invalidate = function (string $event, array $models): void {
            $model = $models[0];
            if (! in_array($model->getTable(), [
                'products', 'product_colors', 'prices', 'stocks', 'product_images',
                'product_options', 'product_categories', 'product_guaranties', 'product_videos',
                'group_products', 'categories', 'brands', 'guaranties', 'comments',
                'discounts', 'discount_items', 'news', 'vlogs', 'vlog_categories',
                'casts', 'cast_categories', 'pages', 'landings', 'landing_products',
                'landing_categories', 'landing_banners', 'banners', 'sliders', 'special_products',
            ], true)) {
                return;
            }
            // View counters are analytics, not content changes.
            if (str_starts_with($event, 'eloquent.saved:') && ! $model->wasRecentlyCreated
                && ! array_diff(array_keys($model->getChanges()), ['view', 'updated_at'])) {
                return;
            }
            $this->app->make(ShopCacheInvalidator::class)->schedule();
        };
        Event::listen('eloquent.saved: *', $invalidate);
        Event::listen('eloquent.deleted: *', $invalidate);
        $this->app->terminating(fn () => $this->app->make(ShopCacheInvalidator::class)->flush());
        // Queue workers are long-lived; flush after each job, not only at exit.
        Event::listen(\Illuminate\Queue\Events\JobProcessed::class,
            fn () => $this->app->make(ShopCacheInvalidator::class)->flush());
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
