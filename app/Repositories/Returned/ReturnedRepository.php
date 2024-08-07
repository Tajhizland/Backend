<?php

namespace App\Repositories\Returned;

use App\Enums\ReturnedStatus;
use App\Models\Returned;
use App\Repositories\Base\BaseRepository;

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
        return  $returned->update(["status" => ReturnedStatus::Accept->value]);
    }
    public function setReject(Returned $returned): mixed
    {
        return  $returned->update(["status" => ReturnedStatus::Reject->value]);
    }
    public function dataTable(): mixed
    {
        return  1;
    }

}
