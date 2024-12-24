<?php

namespace App\Services\Vlog;

use App\Models\Vlog;

interface VlogServiceInterface
{
    public function dataTable();

    public function listing($filters);

    public function findById($id);

    public function getRelatedVlogs($category_id);

    public function findByUrl($url);

    public function view(Vlog $vlog);

    public function store($title, $description, $video,$poster, $url, $status ,$categoryId);

    public function update($id, $title, $description, $video,$poster, $url, $status ,$categoryId);
}
