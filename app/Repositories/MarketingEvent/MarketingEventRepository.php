<?php

namespace App\Repositories\MarketingEvent;

use App\Enums\DeviceType;
use App\Enums\MarketingEventType;
use App\Models\MarketingEvent;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class MarketingEventRepository extends BaseRepository implements MarketingEventRepositoryInterface
{
    /**
     * ستون‌های سبک محصول برای گزارش‌ها؛ ستون‌های سنگین (review/study/meta) لازم نیستند.
     */
    private const PRODUCT_COLUMNS = ['id', 'name', 'url', 'view', 'status', 'is_stock'];

    public function __construct(MarketingEvent $model)
    {
        parent::__construct($model);
    }

    public function record(array $data): mixed
    {
        return $this->model::create($data);
    }

    /**
     * پرتکرارترین محصول‌ها برای یک نوع رویداد (بازدید، سبد، مقایسه، علاقه‌مندی).
     *
     * visitors با COALESCE(user_id, ip) شمرده می‌شود تا کاربر مهمان هم یک بازدیدکننده یکتا حساب شود.
     */
    public function topProducts(string $type, Carbon $from, Carbon $to, int $limit)
    {
        return $this->model::query()
            ->select('product_id')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip)) as visitors')
            ->selectRaw('MAX(created_at) as last_event_at')
            ->where('type', $type)
            ->whereNotNull('product_id')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->with(['product' => fn($query) => $query->select(self::PRODUCT_COLUMNS)->with('images')])
            ->get();
    }

    public function topCategories(string $type, Carbon $from, Carbon $to, int $limit)
    {
        return $this->model::query()
            ->select('category_id')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip)) as visitors')
            ->selectRaw('MAX(created_at) as last_event_at')
            ->where('type', $type)
            ->whereNotNull('category_id')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->with(['category' => fn($query) => $query->select(['id', 'name', 'url'])])
            ->get();
    }

    /**
     * سری زمانی روزانه. گروه‌بندی در SQL انجام می‌شود و تبدیل به شمسی روی نتیجه‌ی جمع‌شده،
     * تا برخلاف گزارش‌های قدیمی کل ردیف‌های بازه در حافظه بارگذاری نشوند.
     */
    public function dailyCounts(string $type, Carbon $from, Carbon $to)
    {
        return $this->model::query()
            ->selectRaw('DATE(created_at) as day')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip)) as visitors')
            ->where('type', $type)
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn($row) => [
                'date' => Jalalian::fromDateTime(Carbon::parse($row->day))->format('Y/m/d'),
                'value' => (int)$row->total,
                'visitors' => (int)$row->visitors,
            ])
            ->values();
    }

    public function countEvents(string $type, Carbon $from, Carbon $to): int
    {
        return $this->model::where('type', $type)
            ->whereBetween('created_at', [$from, $to])
            ->count();
    }

    public function countVisitors(string $type, Carbon $from, Carbon $to): int
    {
        return (int)$this->model::where('type', $type)
            ->whereBetween('created_at', [$from, $to])
            ->distinct()
            ->count(DB::raw('COALESCE(user_id, ip)'));
    }

    /**
     * بازدید/سبد/مقایسه/علاقه‌مندی هر محصول در یک کوئری، برای محاسبه نرخ تبدیل بازدید به سبد.
     *
     * $minViews جلوی این را می‌گیرد که محصولی با ۱ بازدید و ۱ افزودن، ۱۰۰٪ تبدیل نشان دهد.
     */
    public function engagementMatrix(Carbon $from, Carbon $to, int $limit, int $minViews = 1, string $orderBy = 'views')
    {
        $sum = fn(string $type) => "SUM(CASE WHEN type = '{$type}' THEN 1 ELSE 0 END)";

        $query = $this->model::query()
            ->select('product_id')
            ->selectRaw($sum(MarketingEventType::ProductView->value) . ' as views')
            ->selectRaw($sum(MarketingEventType::AddToCart->value) . ' as carts')
            ->selectRaw($sum(MarketingEventType::Compare->value) . ' as compares')
            ->selectRaw($sum(MarketingEventType::Favorite->value) . ' as favorites')
            ->selectRaw($sum(MarketingEventType::Purchase->value) . ' as purchases')
            ->whereIn('type', [
                MarketingEventType::ProductView->value,
                MarketingEventType::AddToCart->value,
                MarketingEventType::Compare->value,
                MarketingEventType::Favorite->value,
                MarketingEventType::Purchase->value,
            ])
            ->whereNotNull('product_id')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('product_id')
            ->havingRaw('views >= ?', [$minViews]);

        $query = $orderBy === 'conversion'
            // محصولاتی که ترافیک می‌گیرند ولی به سبد نمی‌رسند، اول لیست بیایند.
            ? $query->orderByRaw('(carts / views) ASC')->orderByDesc('views')
            : $query->orderByDesc('views');

        return $query->limit($limit)
            ->with(['product' => fn($q) => $q->select(self::PRODUCT_COLUMNS)->with('images')])
            ->get();
    }

    /**
     * تقاضای بی‌پاسخ: محصولاتی که بازدید یا افزودن به سبد داشته‌اند ولی موجودی‌شان صفر است.
     */
    public function unmetDemand(Carbon $from, Carbon $to, int $limit)
    {
        return $this->model::query()
            ->select('product_id')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip)) as visitors')
            ->whereIn('type', [MarketingEventType::ProductView->value, MarketingEventType::AddToCart->value])
            ->whereNotNull('product_id')
            ->whereBetween('created_at', [$from, $to])
            ->whereHas('product', fn($query) => $query->whereDoesntHave(
                'stocks',
                fn($stock) => $stock->where('stock', '>', 0)
            ))
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->with(['product' => fn($query) => $query->select(self::PRODUCT_COLUMNS)->with('images')])
            ->get();
    }

    /**
     * قیف بازدید → سبد → خرید به تفکیک دستگاه.
     *
     * پاسخ به سوال بعدیِ «سهم موبایل چقدر است؟»: اگر سهم بازدید موبایل بالا ولی سهم
     * خریدش پایین باشد، مشکل از تجربه‌ی موبایل است نه از ترافیک.
     */
    public function deviceConversion(Carbon $from, Carbon $to)
    {
        $sum = fn(string $type) => "SUM(CASE WHEN type = '{$type}' THEN 1 ELSE 0 END)";

        return $this->model::query()
            ->selectRaw('COALESCE(device, ?) as device', [DeviceType::Unknown->value])
            ->selectRaw($sum(MarketingEventType::ProductView->value) . ' as views')
            ->selectRaw($sum(MarketingEventType::AddToCart->value) . ' as carts')
            ->selectRaw($sum(MarketingEventType::Purchase->value) . ' as purchases')
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip)) as visitors')
            ->whereIn('type', [
                MarketingEventType::ProductView->value,
                MarketingEventType::AddToCart->value,
                MarketingEventType::Purchase->value,
            ])
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('device')
            ->orderByDesc('views')
            ->get();
    }
}
