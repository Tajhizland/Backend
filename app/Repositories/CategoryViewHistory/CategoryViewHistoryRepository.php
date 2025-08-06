<?php

namespace App\Repositories\CategoryViewHistory;

use App\Models\CategoryViewHistory;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class CategoryViewHistoryRepository extends BaseRepository implements CategoryViewHistoryRepositoryInterface
{
    public function __construct(CategoryViewHistory $model)
    {
        parent::__construct($model);
    }

    public function findTop($userId)
    {
        $latestViews = $this->model::where('user_id', $userId)
            ->latest("id")
            ->limit(20)
            ->get(['category_id']);

        // شمارش تعداد هر category_id
        $categoryCounts = $latestViews->countBy('category_id');

        // پیدا کردن category_id با بیشترین تعداد
        $mostViewedCategory = $categoryCounts->keys()->first();

        return $mostViewedCategory;
    }
    public function findTops($userId)
    {
        $latestViews = $this->model::where('user_id', $userId)
            ->latest("id")
            ->limit(20)
            ->get(['category_id']);

        // شمارش تعداد هر category_id و مرتب‌سازی نزولی
        $categoryCounts = $latestViews->countBy('category_id')
            ->sortByDesc(function ($count) {
                return $count;
            });

        // گرفتن سه category_id برتر
        $topCategories = $categoryCounts->keys()->take(3)->toArray();

        return $topCategories;
    }

    public function findTopByIp($ip)
    {
        $latestViews = $this->model::where('ip', $ip)
            ->latest("id")
            ->limit(20)
            ->get(['category_id']);

        // شمارش تعداد هر category_id
        $categoryCounts = $latestViews->countBy('category_id');

        // پیدا کردن category_id با بیشترین تعداد
        $mostViewedCategory = $categoryCounts->keys()->first();

        return $mostViewedCategory;
    }
    public function findTopsByIp($ip)
    {
        $latestViews = $this->model::where('ip', $ip)
            ->latest("id")
            ->limit(20)
            ->get(['category_id']);

        // شمارش تعداد هر category_id
        $categoryCounts = $latestViews->countBy('category_id');

        // پیدا کردن category_id با بیشترین تعداد
        $mostViewedCategory = $categoryCounts->keys()->take(3)->toArray();

        return $mostViewedCategory;
    }
}
