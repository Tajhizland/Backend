<?php

namespace App\Services\Vlog;

interface VlogServiceInterface
{
    public function dataTable();

    public function listing();

    public function findById($id);

    public function findByUrl($url);

    public function store($title, $description, $video,$poster, $url, $status);

    public function update($id, $title, $description, $video,$poster, $url, $status);
}
