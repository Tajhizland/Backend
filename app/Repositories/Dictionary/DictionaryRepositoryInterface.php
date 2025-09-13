<?php

namespace App\Repositories\Dictionary;

use App\Repositories\Base\BaseRepositoryInterface;

interface DictionaryRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function findByOriginalWord($originalWord);
}
