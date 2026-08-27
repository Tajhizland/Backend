<?php

namespace App\DTOs\HomePage;

use Illuminate\Support\Collection;

/**
 * دیتای خام صفحه اصلی؛ خروجی HomePageService و ورودی HomePageResource.
 *
 * وجود این DTO باعث می‌شود Resource دیگر با آرایه‌ی بدون تایپ کار نکند
 * و اضافه/کم شدن یک بخش از صفحه اصلی فقط در یک نقطه تعریف شود.
 */
final class HomePageData
{
    public function __construct(
        public readonly mixed      $campaign,
        public readonly mixed      $pendingCampaign,
        public readonly mixed      $discountTimer,
        public readonly Collection $topDiscountedProducts,
        public readonly Collection $specialProducts,
        /** محصولات تصادفیِ بخش «منتخب تجهیزلند» */
        public readonly Collection $randomProducts,
        public readonly Collection $homePageCategories,
        public readonly Collection $concepts,
        public readonly Collection $brands,
        public readonly Collection $trustedBrands,
        public readonly Collection $vlogs,
        public readonly Collection $news,
        public readonly Collection $posters,
        /** اسلایدرها گروه‌بندی‌شده بر اساس type */
        public readonly Collection $sliderGroups,
        /** بنرها گروه‌بندی‌شده بر اساس type */
        public readonly Collection $bannerGroups,
    )
    {
    }

    public function sliders(string $type): Collection
    {
        return $this->sliderGroups->get($type) ?? new Collection();
    }

    public function banners(string $type): Collection
    {
        return $this->bannerGroups->get($type) ?? new Collection();
    }
}
