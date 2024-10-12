<?php

namespace App\Services\Search;

interface SearchServiceInterface
{
    public function searchQuery($query);
    public function searchPaginate($query);
}
