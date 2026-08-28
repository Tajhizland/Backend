<?php

namespace App\Repositories\RequestLog;

use App\Models\RequestLog;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class RequestLogRepository extends BaseRepository implements RequestLogRepositoryInterface
{
    public function __construct(RequestLog $model)
    {
        parent::__construct($model);
    }

    public function store(?string $title, ?string $request, ?string $response): mixed
    {
        return $this->model::create([
            "title" => $title,
            "request" => $request,
            "response" => $response,
        ]);
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
