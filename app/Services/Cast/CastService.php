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
        return $this->castRepository->findWithVlog($id);
    }

    public function dataTable()
    {
        return $this->castRepository->dataTable();
    }

    public function store($title, $image, $description, $url, $status, $audio, $vlog_id)
    {
        $audioPath = $this->s3Service->upload($audio, "cast/audio");
        $imagePath = $this->s3Service->upload($audio, "cast/image");
        return $this->castRepository->create([
            'title' => $title,
            'image' => $imagePath,
            'description' => $description,
            'url' => $url,
            'status' => $status,
            'audio' => $audioPath,
            'vlog_id' => $vlog_id
        ]);
    }

    public function update($id, $title, $image, $description, $url, $status, $audio, $vlog_id)
    {
        $cast = $this->castRepository->findOrFail($id);
        $audioPath = $cast->audio;
        $imagePath = $cast->image;
        if ($audio) {
            $this->s3Service->remove("cast/audio/" . $audioPath);
            $audioPath = $this->s3Service->upload($audio, "cast/audio");
        }
        if ($image) {
            $this->s3Service->remove("cast/image/" . $imagePath);
            $imagePath = $this->s3Service->upload($audio, "cast/image");
        }
        return $cast->update($cast, [
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'status' => $status,
            'audio' => $audioPath,
            'image' => $imagePath,
            'vlog_id' => $vlog_id
        ]);
    }

}
