<?php

namespace App\Services\Cast;

interface CastServiceInterface
{
    public function paginated();

    public function dataTable();

    public function find($id);

    public function findByUrl($url);

    public function store($title, $image, $description, $url, $status, $audio, $vlog_id, $category_id);

    public function update($id, $title, $image, $description, $url, $status, $audio, $vlog_id, $category_id);

}
