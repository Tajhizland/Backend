<?php

namespace App\Repositories\OnHoldOrder;

use App\Enums\OnHoldOrderStatus;
use App\Models\OnHoldOrder;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;

class OnHoldOrderRepository extends BaseRepository implements OnHoldOrderRepositoryInterface
{
    public function __construct(OnHoldOrder $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(OnHoldOrder::class)
            ->allowedFilters(['order_id', 'expire_date', 'status'])
            ->allowedSorts(['order_id', 'expire_date', 'status'])
            ->paginate($this->pageSize);
    }
    public function createOnHoldOrder($orderId){
        return $this->create([
            "order_id"=>$orderId,
            "status"=>OnHoldOrderStatus::Pending->value
        ]);
    }

    public function userOnHoldOrderPaginate($userId)
    {
        return $this->model::where("user_id",$userId)->latest("id")->paginate();
    }

    public function setReject(OnHoldOrder $onHoldModel)
    {
        $onHoldModel->update(
            [
                "review_date" => Carbon::now(),
                "status" => OnHoldOrderStatus::Reject->value
            ]
        );
    }

    public function setAccept(OnHoldOrder $onHoldModel)
    {
        $onHoldModel->update(
            [
                "review_date" => Carbon::now(),
                "expire_date" => Carbon::now()->addHours(config("settings.order_payment_expire_hour")),
                "status" => OnHoldOrderStatus::Accept->value
            ]
        );
    }
}
