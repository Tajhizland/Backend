<?php

namespace App\Repositories\SearchLog;

use App\Models\SearchLog;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;
use Spatie\QueryBuilder\QueryBuilder;

class SearchLogRepository extends BaseRepository implements SearchLogRepositoryInterface
{
    public function __construct(SearchLog $model)
    {
        parent::__construct($model);
    }

    public function record(array $data): mixed
    {
        return $this->model::create($data);
    }

    /**
     * پرجستجوترین عبارت‌ها. گروه‌بندی روی normalized_term است تا «یخچال» و «یخچال » یکی شمرده شوند،
     * ولی متن نمایشی از term خام گرفته می‌شود.
     */
    public function topTerms(Carbon $from, Carbon $to, int $limit)
    {
        return $this->baseTermQuery($from, $to)
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn($row) => $this->presentTerm($row));
    }

    /**
     * جستجوهای بی‌نتیجه: هر عبارتی که در بازه حتی یک‌بار هم صفر نتیجه گرفته است.
     *
     * ملاک product_count است نه result_count: جستجویی که فقط یک مقاله برگردانده هم
     * از نگاه فروش بی‌نتیجه است. مرتب‌سازی بر اساس تعداد دفعات بی‌نتیجه‌ماندن است، نه کل
     * جستجو؛ چون همین عدد نشان می‌دهد چند بار مشتری چیزی خواسته که فروشگاه نداشته.
     */
    public function zeroResultTerms(Carbon $from, Carbon $to, int $limit)
    {
        return $this->baseTermQuery($from, $to)
            ->havingRaw('no_product_results > 0')
            ->orderByDesc('no_product_results')
            ->limit($limit)
            ->get()
            ->map(fn($row) => $this->presentTerm($row));
    }

    public function dailyCounts(Carbon $from, Carbon $to)
    {
        return $this->model::query()
            ->selectRaw('DATE(created_at) as day')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN result_count = 0 THEN 1 ELSE 0 END) as zero_results')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn($row) => [
                'date' => Jalalian::fromDateTime(Carbon::parse($row->day))->format('Y/m/d'),
                'value' => (int)$row->total,
                'zeroResults' => (int)$row->zero_results,
            ])
            ->values();
    }

    public function totals(Carbon $from, Carbon $to): array
    {
        $row = $this->model::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT normalized_term) as unique_terms')
            ->selectRaw('SUM(CASE WHEN result_count = 0 THEN 1 ELSE 0 END) as zero_results')
            ->selectRaw('SUM(CASE WHEN product_count = 0 THEN 1 ELSE 0 END) as no_product_results')
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip)) as visitors')
            ->whereBetween('created_at', [$from, $to])
            ->first();

        $total = (int)($row->total ?? 0);
        $zeroResults = (int)($row->zero_results ?? 0);

        return [
            'total' => $total,
            'uniqueTerms' => (int)($row->unique_terms ?? 0),
            'zeroResults' => $zeroResults,
            'noProductResults' => (int)($row->no_product_results ?? 0),
            'visitors' => (int)($row->visitors ?? 0),
            'zeroResultRate' => $total > 0 ? round($zeroResults / $total * 100, 1) : 0.0,
        ];
    }

    /**
     * آخرین جستجوهای همین بازدیدکننده در بازه‌ی کوتاه اخیر، برای تشخیص تایپ تدریجی.
     */
    public function recentForVisitor(?string $sessionId, ?string $ip, Carbon $since, int $limit = 10)
    {
        return $this->model::query()
            ->where('created_at', '>=', $since)
            ->when(
                $sessionId,
                fn($query) => $query->where('session_id', $sessionId),
                fn($query) => $query->where('ip', $ip)
            )
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function dataTable()
    {
        return QueryBuilder::for(SearchLog::class)
            ->allowedFilters(...['id', 'term', 'normalized_term', 'result_count', 'product_count', 'ip', 'user_id'])
            ->allowedSorts(...['id', 'term', 'result_count', 'product_count', 'created_at'])
            ->latest("id")
            ->paginate($this->pageSize);
    }

    private function baseTermQuery(Carbon $from, Carbon $to)
    {
        return $this->model::query()
            ->select('normalized_term')
            ->selectRaw('MIN(term) as term')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip)) as visitors')
            ->selectRaw('AVG(result_count) as avg_results')
            ->selectRaw('SUM(CASE WHEN result_count = 0 THEN 1 ELSE 0 END) as zero_results')
            ->selectRaw('SUM(CASE WHEN product_count = 0 THEN 1 ELSE 0 END) as no_product_results')
            ->selectRaw('MAX(created_at) as last_searched_at')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('normalized_term');
    }

    private function presentTerm($row): array
    {
        $total = (int)$row->total;
        $zeroResults = (int)$row->zero_results;

        return [
            'term' => $row->term,
            'normalizedTerm' => $row->normalized_term,
            'total' => $total,
            'visitors' => (int)$row->visitors,
            'avgResults' => round((float)$row->avg_results, 1),
            'zeroResults' => $zeroResults,
            'noProductResults' => (int)$row->no_product_results,
            'zeroResultRate' => $total > 0 ? round($zeroResults / $total * 100, 1) : 0.0,
            'lastSearchedAt' => $row->last_searched_at
                ? Jalalian::fromDateTime(Carbon::parse($row->last_searched_at))->format('Y/m/d H:i')
                : null,
        ];
    }
}
