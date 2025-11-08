<?php

namespace App\Services\Cast;

use App\Repositories\Cast\CastRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class CastService implements CastServiceInterface
{
    public function __construct
    (
        private CastRepositoryInterface $castRepository,
        private S3ServiceInterface      $s3Service
    )
    {
    }

    public function find($id)
    {
        return $this->castRepository->findOrFail($id);
    }

    public function dataTable()
    {
        return $this->castRepository->dataTable();
    }

    public function store($title, $description, $url, $status, $audio, $vlog_id)
    {
        $path = $this->s3Service->upload($audio, "cast");
        return $this->castRepository->create([
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'status' => $status,
            'audio' => $path,
            'vlog_id' => $vlog_id
        ]);
    }

    public function update($id, $title, $description, $url, $status, $audio, $vlog_id)
    {
        $cast = $this->castRepository->findOrFail($id);
        $path = $cast->audio;
        if ($audio) {
            $this->s3Service->remove("cast/" . $path);
            $path = $this->s3Service->upload($audio, "cast");
        }
        return $cast->update($cast, [
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'status' => $status,
            'audio' => $path,
            'vlog_id' => $vlog_id
        ]);
    }

}
