<?php

namespace App\Repositories\Delivery;

use App\Models\Delivery;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class DeliveryRepository extends BaseRepository implements DeliveryRepositoryInterface
{
    public function __construct(Delivery $model)
    {
        parent::__construct($model);
    }

    public function getActiveDelivery()
    {
        return $this->model::active()->get();
    }

    public function dataTable()
    {
        return QueryBuilder::for(Delivery::class)
            ->allowedFilters(['name', 'description', 'status', 'id', 'price', 'created_at'])
            ->allowedSorts(['name', 'description', 'status', 'id', 'price', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function createDelivery($name, $status, $description, $price, $logo)
    {
        $this->create([
            "name" => $name,
            "status" => $status,
            "description" => $description,
            "price" => $price,
            "logo" => $logo,
        ]);
    }

    public function updateDelivery(Delivery $delivery, $name, $status, $description, $price, $logo)
    {
        $delivery->update(
            [
                "name" => $name,
                "status" => $status,
                "description" => $description,
                "price" => $price,
                "logo" => $logo,
            ]
        );
    }
}
