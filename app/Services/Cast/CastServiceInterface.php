<?php

namespace App\Services\Cast;

interface CastServiceInterface
{
    public function dataTable();

    public function find($id);

    public function store($title, $description, $url, $status, $audio, $vlog_id);

    public function update($id, $title, $description, $url, $status, $audio, $vlog_id);

}
