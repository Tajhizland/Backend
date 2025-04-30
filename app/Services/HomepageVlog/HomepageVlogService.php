<?php

namespace App\Services\HomepageVlog;

use App\Repositories\HomepageVlog\HomepageVlogRepositoryInterface;

class HomepageVlogService implements HomepageVlogServiceInterface
{
    public function __construct
    (
        private HomepageVlogRepositoryInterface $homepageVlogRepository
    )
    {
    }

    public function get()
    {
        return $this->homepageVlogRepository->all();
    }

    public function update($id, $vlogId)
    {
        $homepageVlog = $this->homepageVlogRepository->findOrFail($id);
        return $this->homepageVlogRepository->update($homepageVlog, [
            "vlog_id" => $vlogId
        ]);
    }
}
