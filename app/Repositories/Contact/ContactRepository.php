<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    public function __construct(Contact $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Contact::class)
            ->allowedFilters(['name', 'email', 'message', 'id', 'created_at'])
            ->allowedSorts(['name', 'email', 'message', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function store($name, $email, $message)
    {
        return $this->model::create([
            "name" => $name,
            "email" => $email,
            "message" => $message,
        ]);
    }
}
