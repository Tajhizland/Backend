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
        return $this->model::with(["orderItems.product", "orderItems.productColor"])->where("user_id", $userId)->paid()->latest("id")->paginate($this->pageSize);
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
            ->paginate($this->pageSize);
    }

    public function createOrder($user_id, $order_info_id, $price, $delivery_price, $final_price, $status, $payment_method, $delivery_method, $order_date, $delivery_date, $tracking_number)
    {
        $payment_method=$payment_method??config("settings.default_gateway");
        return $this->create(
            [
                "user_id" => $user_id,
                "order_info_id" => $order_info_id,
                "price" => $price,
                "delivery_price" => $delivery_price,
                "final_price" => $final_price,
                "status" => $status,
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
        return $this->model::with(["delivery", "payment", "orderInfo", "orderInfo.city", "orderInfo.province", "orderItems.product", "orderItems.productColor"])
            ->where("id", $id)
            ->first();
    }
    public function totalPriceChartData()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        return $this->model::where('order_date', '>=', $thirtyDaysAgo)
            ->paid()
            ->get()
            ->groupBy(function ($order) {
                return Jalalian::fromDateTime($order->order_date)->format('Y/m/d');
            })
            ->map(function ($orders, $date) {
                return [
                    'date' => $date,
                    'value' => $orders->sum("final_price"),
                ];
            })
            ->values();

    }
    public function totalCountChartData()
    {   $thirtyDaysAgo = Carbon::now()->subDays(30);

        return $this->model::where('order_date', '>=', $thirtyDaysAgo)
            ->paid()
            ->get()
            ->groupBy(function ($order) {
                return Jalalian::fromDateTime($order->order_date)->format('Y/m/d');
            })
            ->map(function ($orders, $date) {
                return [
                    'date' => $date,
                    'value' => $orders->count(),
                ];
            })
            ->values();
    }

    public function todayOrderCount()
    {
        return $this->model::whereDate('order_date', Carbon::today())->paid()->count();
    }
}
