<?php

namespace App\Services\Poster;

interface PosterServiceInterface
{
    public function dataTable();

    public function store($image);

    public function findById($id);

    public function update($id,$image);


}
