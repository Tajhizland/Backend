<?php

namespace App\Repositories\RequestLog;

use App\Models\RequestLog;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class RequestLogRepository extends BaseRepository implements RequestLogRepositoryInterface
{
    public function __construct(RequestLog $model)
    {
        parent::__construct($model);
    }

    public function store(?string $title, ?string $request, ?string $response): mixed
    {
        $attributes = [
            "title" => $title,
            "request" => $request,
            "response" => $response,
        ];
        $connection = $this->model->getConnection();

        if ($connection->transactionLevel() === 0) {
            return $this->model->newQuery()->create($attributes);
        }

        // Failed payments roll back the order transaction; diagnostics must survive it.
        $logConnection = DB::build(array_replace($connection->getConfig(), [
            'name' => $connection->getName() . '_request_logs',
        ]));

        try {
            return $this->model->on($logConnection->getName())->create($attributes)
                ->setConnection($connection->getName());
        } finally {
            DB::purge($logConnection->getName());
        }
    }

    public function dataTable()
    {
        return QueryBuilder::for(RequestLog::class)
            ->allowedFilters(...['id', 'title'])
            ->allowedSorts(...['id', 'title', 'created_at'])
            ->latest("id")
            ->paginate($this->pageSize);
    }
}
