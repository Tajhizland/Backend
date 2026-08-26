<?php

namespace App\Services\HomepageVlog;

use App\DTOs\HomepageVlog\HomepageVlogUpdateDto;
use App\Repositories\HomepageVlog\HomepageVlogRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class HomepageVlogService implements HomepageVlogServiceInterface
{
    public function __construct
    (
        private HomepageVlogRepositoryInterface $homepageVlogRepository
    )
    {
    }

    public function get(): mixed
    {
        return $this->homepageVlogRepository->getWithVlog();
    }

    public function find(int $id): mixed
    {
        $homepageVlog = $this->homepageVlogRepository->find($id);
        if (!$homepageVlog) {
            throw new NotFoundHttpException();
        }
        return $homepageVlog;
    }

    public function update(HomepageVlogUpdateDto $dto): bool
    {
        $homepageVlog = $this->find($dto->homepageVlogId);
        return $this->homepageVlogRepository->update($homepageVlog, [
            "vlog_id" => $dto->vlogId,
        ]);
    }
}
