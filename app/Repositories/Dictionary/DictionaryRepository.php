<?php

namespace App\Repositories\Dictionary;

use App\Models\Dictionary;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class DictionaryRepository extends BaseRepository implements DictionaryRepositoryInterface
{
    public function __construct(Dictionary $model)
    {
        parent::__construct($model);
    }

    public function findByOriginalWord($originalWord)
    {
        return $this->model::where("original_word", $originalWord)->first();
    }

    public function dataTable()
    {
        return QueryBuilder::for(Dictionary::class)
            ->allowedFilters(['id', 'original_word', ',mean', 'id', 'created_at', 'updated_at'])
            ->allowedSorts(['id', 'original_word', ',mean', 'id', 'created_at', 'updated_at'])
            ->paginate($this->pageSize);
    }
}
