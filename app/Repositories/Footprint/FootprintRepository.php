<?php

namespace App\Repositories\Footprint;

use App\Enums\DeviceType;
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

    /**
     * سهم هر دستگاه از بازدید صفحات.
     *
     * ردیف‌های قدیمی device ندارند، پس با COALESCE در گروه «نامشخص» می‌نشینند و
     * از مجموع حذف نمی‌شوند؛ اینطور درصدها با کل بازدید واقعی جور درمی‌آید.
     */
    public function deviceBreakdown(Carbon $from, Carbon $to)
    {
        return $this->model::query()
            ->selectRaw("COALESCE(device, ?) as device", [DeviceType::Unknown->value])
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip)) as visitors')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('device')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * روند روزانه به تفکیک دستگاه، از قبل pivot شده تا فرانت فقط سری‌ها را بکشد.
     */
    public function deviceDailyCounts(Carbon $from, Carbon $to)
    {
        $query = $this->model::query()
            ->selectRaw('DATE(created_at) as day')
            ->selectRaw('COUNT(*) as total');

        foreach (DeviceType::cases() as $device) {
            $query->selectRaw(
                "SUM(CASE WHEN COALESCE(device, ?) = ? THEN 1 ELSE 0 END) as `{$device->value}`",
                [DeviceType::Unknown->value, $device->value]
            );
        }

        return $query->whereBetween('created_at', [$from, $to])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(function ($row) {
                $point = [
                    'date' => Jalalian::fromDateTime(Carbon::parse($row->day))->format('Y/m/d'),
                    'total' => (int)$row->total,
                ];

                foreach (DeviceType::cases() as $device) {
                    $point[$device->value] = (int)$row->{$device->value};
                }

                return $point;
            })
            ->values();
    }

    /**
     * توزیع یک ستون توصیفی (سیستم‌عامل یا مرورگر) در بازه‌ی داده‌شده.
     */
    public function attributeBreakdown(string $column, Carbon $from, Carbon $to, int $limit)
    {
        if (!in_array($column, ['platform', 'browser'], true)) {
            throw new \InvalidArgumentException("ستون غیرمجاز برای گزارش: {$column}");
        }

        return $this->model::query()
            ->selectRaw("COALESCE(`{$column}`, ?) as name", ['نامشخص'])
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip)) as visitors')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('name')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }
}
