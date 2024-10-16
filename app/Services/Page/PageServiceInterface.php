<?php

namespace App\Services\Page;

interface PageServiceInterface
{
    public function dataTable();
    public function findById($id);
    public function findByUrl($url);
    public function store($title , $url , $image , $content , $status);
    public function update($id ,$title , $url , $image , $content , $status);
}
