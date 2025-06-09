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

    public function allChartData($fromDate,$toDate)
    {
        if ($fromDate) {
            $startDate = Carbon::parse($fromDate);
        } else {
            $startDate = Carbon::now()->subDays(30);
        }

        if ($toDate) {
            $endDate = Carbon::parse($toDate);
        } else {
            $endDate = Carbon::now();
        }

        return $this->model::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
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

    public function ipChartData($fromDate,$toDate)
    {
        if ($fromDate) {
            $startDate = Carbon::parse($fromDate);
        } else {
            $startDate = Carbon::now()->subDays(30);
        }

        if ($toDate) {
            $endDate = Carbon::parse($toDate);
        } else {
            $endDate = Carbon::now();
        }

        return $this->model::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
             ->get()
            ->groupBy(function ($log) {
                return Jalalian::fromDateTime($log->created_at)->format('Y/m/d'); // گروه‌بندی بر اساس تاریخ
            })
            ->map(function ($logs, $date) {
                return [
                    'date' => $date,
                    'value' => $logs->pluck('ip')->unique()->count()
                    ];
            })
            ->values();

    }
}
