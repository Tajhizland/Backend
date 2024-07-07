<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all($columns = ['*']): mixed;

    public function paginate($limit = null, $columns = ['*']): mixed;

    public function create(array $data): mixed;

    public function findOrFail(int $id): mixed;

    public function update(Model $entity, array $data): bool;

    public function delete(Model $entity): bool|null;

    public function updateOrCreate(array $attributes, array $values): mixed;

    public function get(array $condition = [], bool $takeOne = true): mixed;
}
