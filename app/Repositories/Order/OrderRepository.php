<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Models\Transaction;
use App\Repositories\Base\BaseRepository;
use App\Services\Sort\Transaction\SortTransactionByUserMobile;
use App\Services\Sort\Transaction\SortTransactionByUserName;
use App\Services\Sort\TransactionByUserMobileSort;
use App\Services\Sort\TransactionByUserSort;
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
        return $this->model::where("user_id", $userId)->paid()->latest("id")->paginate($this->pageSize);
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

    public function createOrder($user_id, $order_info_id, $price, $discount,$total_price, $final_price, $status, $payment_method,  $delivery_method, $delivery_price ,$order_date, $tracking_number=null)
    {
       return $this->create(
            [
                "user_id" => $user_id,
                "order_info_id" => $order_info_id,
                "price" => $price,
                "discount" => $discount,
                "final_price" => $final_price,
                "status" => $status,
                "payment_method" => $payment_method,
                "delivery_method" => $delivery_method,
                "delivery_price" => $delivery_price,
                "order_date" => $order_date,
                "tracking_number" => $tracking_number,
            ]
        );
    }
    public function updateOrderStatus(Order $order , $status){
        return $order->update(["status" => $status]);
    }

    public function findWithDetails($id){
        return $this->model::with(["orderInfo", "orderItems.product", "orderItems.color"])
    ->where("id", $id)
    ->first(); 
    }
}
