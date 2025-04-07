<?php

namespace App\Repositories\Footprint;

use App\Models\Footprint;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class FootprintRepository extends BaseRepository implements FootprintRepositoryInterface
{
    public function __construct(Footprint $model)
    {
        parent::__construct($model);
    }

    public function allChartData()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        return $this->model::where('created_at', '>=', $thirtyDaysAgo)
            ->get()
            ->groupBy(function ($log) {
                return Jalalian::fromDateTime($log->created_at)->format('Y/m/d');
            })
            ->map(function ($logs, $date) {
                return [
                    'date' => $date,
                    'value' => $logs->count(),
                ];
            })
            ->values();
    }

    public function ipChartData()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        return $this->model::where('created_at', '>=', $thirtyDaysAgo)
            ->get()
            ->groupBy(function ($log) {
                return Jalalian::fromDateTime($log->created_at)->format('Y/m/d');
            })
            ->map(function ($logs, $date) {
                return [
                    'date' => $date,
                    'value' => $logs->count(),
                ];
            })
            ->values();
    }
}
