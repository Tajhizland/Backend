<?php

namespace App\Repositories\Gateway;

use App\Models\Gateway;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class GatewayRepository extends BaseRepository implements GatewayRepositoryInterface
{
    public function __construct(Gateway $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Gateway::class)
            ->allowedFilters(['name', 'description', 'status', 'id', 'created_at'])
            ->allowedSorts(['name', 'description', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function findActiveGateway()
    {
        return $this->model::active()->first();
    }

    public function createGateway($name, $status, $description)
    {
        $this->create(
            [
                "name" => $name,
                "status" => $status,
                "description" => $description,
            ]
        );
    }

    public function updateGateway(Gateway $gateway, $name, $status, $description)
    {
        $gateway->update(
            [
                "name" => $name,
                "status" => $status,
                "description" => $description,
            ]
        );
    }

    public function activeCountExceptThis($id)
    {
        return $this->model::active()->where("id", "<>", $id)->count();
    }
}
