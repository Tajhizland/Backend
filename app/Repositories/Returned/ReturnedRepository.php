<?php

namespace App\Repositories\Returned;

use App\Enums\ReturnedStatus;
use App\Models\Returned;
use App\Repositories\Base\BaseRepository;
use App\Services\Sort\Returned\SortReturnedByProductName;
use App\Services\Sort\Returned\SortReturnedByUserMobile;
use App\Services\Sort\Returned\SortReturnedByUserName;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ReturnedRepository extends BaseRepository implements ReturnedRepositoryInterface
{
    public function __construct(Returned $model)
    {
        parent::__construct($model);
    }

    public function findByOrderItemId(int $orderItemId): mixed
    {
        return $this->model::where("order_item_id", $orderItemId)->first();
    }

    public function createReturned($orderId, $orderItemId, $userId, $count, $description, $file): mixed
    {
        return $this->model::create([
            "order_id" => $orderId,
            "user_id" => $userId,
            "order_item_id" => $orderItemId,
            "count" => $count,
            "status" => ReturnedStatus::Pending->value,
            "description" => $description,
            "file" => $file,
        ]);
    }

    public function setAccept(Returned $returned): mixed
    {
        return $returned->update(["status" => ReturnedStatus::Accept->value]);
    }

    public function setReject(Returned $returned): mixed
    {
        return $returned->update(["status" => ReturnedStatus::Reject->value]);
    }

    public function dataTable(): mixed
    {
        return QueryBuilder::for(Returned::class)
            ->allowedFilters(['user_id', 'order_id', 'count', 'description', 'status', 'created_at',
                AllowedFilter::callback('user', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }), AllowedFilter::callback('mobile', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('username', 'like', '%' . $value . '%');
                    });
                }),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('orderItem', function ($query) use ($value) {
                        $query->whereHas('product', function ($query) use ($value) {
                            $query->where('name', 'like', '%' . $value . '%');
                        });
                    });
                })])
            ->allowedSorts(['user_id', 'order_id', 'count', 'description', 'status', 'created_at'
                , AllowedSort::custom("product", new SortReturnedByProductName())
                , AllowedSort::custom("mobile", new SortReturnedByUserMobile())
                , AllowedSort::custom("user", new SortReturnedByUserName())])
            ->paginate($this->pageSize);
    }

}
