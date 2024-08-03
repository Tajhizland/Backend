<?php

namespace App\Services\New;

use App\Repositories\New\NewRepositoryInterface;

class NewService implements  NewServiceInterface
{
    public function __construct(
        private NewRepositoryInterface $newRepository
    )
    {
    }

    public function findByUrl($url)
    {
        return $this->newRepository->findByUrl($url);
    }
    public function activePaginate()
    {
        return $this->newRepository->activePaginate();
    }
}
