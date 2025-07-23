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
        return  DB::table('category_view_histories')
            ->select('category_id', DB::raw('COUNT(*) as view_count'))
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->groupBy('category_id')
            ->orderBy('view_count', 'desc')
            ->first();
    }
}
