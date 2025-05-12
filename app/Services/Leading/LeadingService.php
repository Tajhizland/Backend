<?php

namespace App\Services\Leading;

use App\Repositories\New\NewRepositoryInterface;
use App\Repositories\Poster\PosterRepositoryInterface;
use App\Repositories\Vlog\VlogRepositoryInterface;

class LeadingService implements LeadingServiceInterface
{
    public function __construct
    (
        private VlogRepositoryInterface   $vlogRepository,
        private NewRepositoryInterface    $newRepository,
        private PosterRepositoryInterface $posterRepository
    )
    {
    }

    public function index()
    {
        $poster = $this->posterRepository->findOrFail(2);
        $lastNews = $this->newRepository->getLastActiveNews();
        $vlog = $this->vlogRepository->getByCategory(4);
        return [
            "poster" => $poster,
            "blog" => $lastNews,
            "vlog" => $vlog
        ];
    }
}
