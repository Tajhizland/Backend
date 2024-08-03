<?php

namespace App\Services\New;

interface NewServiceInterface
{
    public function findByUrl($url);
    public function activePaginate();
}
