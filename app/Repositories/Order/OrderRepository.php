<?php

namespace App\Repositories\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Repositories\Base\BaseRepository;
use App\Services\Sort\Transaction\SortTransactionByUserMobile;
use App\Services\Sort\Transaction\SortTransactionByUserName;
use App\Services\Sort\TransactionByUserMobileSort;
use App\Services\Sort\TransactionByUserSort;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function userOrderPaginate($userId)
    {
        return $this->model::with(["orderItems.product", "orderItems.productColor", "orderItems.guaranty"])->where("user_id", $userId)->paid()->latest("id")->paginate($this->pageSize);
    }

    public function setStatus(Order $order, $status)
    {
        return $order->update(["status" => $status]);
    }

    public function onHoldDataTable()
    {
        return $this->model::hasOnHoldPending()->latest("id")->paginate($this->pageSize);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Order::class)
            ->allowedFilters(['user_id', 'final_price', 'status', 'payment_method', 'delivery_method', 'order_date',
                AllowedFilter::callback('user', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
                AllowedFilter::callback('mobile', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('username', 'like', '%' . $value . '%');
                    });
                })
            ])
            ->allowedSorts(['user_id', 'order_id', 'track_id', 'price'
                , AllowedSort::custom("user", new SortTransactionByUserName())
                , AllowedSort::custom("mobile", new SortTransactionByUserMobile())
            ])
            ->latest("id")
            ->paginate($this->pageSize);
    }

    public function createOrder($user_id, $order_info_id, $price, $delivery_price, $final_price, $status, $payment_method, $delivery_method, $order_date, $delivery_date, $tracking_number, $total_price = 0, $use_wallet_price = 0, $off = 0)
    {
        $payment_method = $payment_method ?? config("settings.default_gateway");
        return $this->create(
            [
                "user_id" => $user_id,
                "total_price" => $total_price,
                "use_wallet_price" => $use_wallet_price,
                "order_info_id" => $order_info_id,
                "price" => $price,
                "delivery_price" => $delivery_price,
                "final_price" => $final_price,
                "status" => $status,
                "off" => $off,
                "payment_method" => $payment_method,
                "delivery_method" => $delivery_method,
                "order_date" => $order_date,
                "delivery_date" => $delivery_date,
                "tracking_number" => $tracking_number,
            ]
        );
    }

    public function updateOrderStatus(Order $order, $status)
    {
        return $order->update(["status" => $status]);
    }

    public function findWithDetails($id)
    {
        return $this->model::with(["delivery", "payment", "orderInfo", "orderInfo.city", "orderInfo.province", "orderItems.product", "orderItems.productColor", "orderItems.guaranty"])
            ->where("id", $id)
            ->first();
    }

    public function totalPriceChartData($fromDate, $toDate)
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
        // گرفتن سفارش‌های پرداخت‌شده و جمع زدن بر اساس روز شمسی
        $data = $this->model::whereBetween('order_date', [$startDate, $endDate])
            ->paid()
            ->get()
            ->groupBy(function ($order) {
                return Jalalian::fromDateTime($order->order_date)->format('Y/m/d');
            })
            ->mapWithKeys(function ($orders, $date) {
                return [$date => $orders->sum('final_price')];
            });

        // ساخت لیست همه روزها از ۳۰ روز پیش تا امروز (شمسی)
        $dates = collect();
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dates->push(Jalalian::fromDateTime($date)->format('Y/m/d'));
        }

        // ساخت خروجی نهایی با مقدار 0 برای روزهای بدون سفارش
        $final = $dates->map(function ($date) use ($data) {
            return [
                'date' => $date,
                'value' => $data[$date] ?? 0,
            ];
        });

        return $final->values();

    }

    public function totalCountChartData($fromDate, $toDate)
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
        // مرحله 1: خروجی اصلی
        $data = $this->model::where('order_date', '>=', $startDate)
            ->paid()
            ->get()
            ->groupBy(function ($order) {
                return Jalalian::fromDateTime($order->order_date)->format('Y/m/d');
            })
            ->mapWithKeys(function ($orders, $date) {
                return [$date => $orders->count()];
            });

        // مرحله 2: لیست تاریخ‌ها از ۳۰ روز پیش تا امروز
        $dates = collect();
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dates->push(Jalalian::fromDateTime($date)->format('Y/m/d'));
        }

        // مرحله 3: ترکیب داده‌ها با مقدار صفر برای روزهای بدون سفارش
        $final = $dates->map(function ($date) use ($data) {
            return [
                'date' => $date,
                'value' => $data[$date] ?? 0,
            ];
        });

        return $final->values();
    }

    public function todayOrderCount()
    {
        return $this->model::whereDate('order_date', Carbon::today())->paid()->count();
    }
}
