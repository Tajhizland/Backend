<?php

namespace App\Services\Vlog;

use App\Models\Vlog;

interface VlogServiceInterface
{
    public function dataTable();
    public function listing($filters);
    public function search($query);
    public function getMostViewed();
    public function findById($id);
    public function getRelatedVlogs($category_id ,$except);
    public function findByUrl($url);
    public function view(Vlog $vlog);
    public function store($title, $description, $video,$poster, $url, $status ,$categoryId ,$author);
    public function update($id, $title, $description, $video,$poster, $url, $status ,$categoryId);
    public function getSitemapData();
    public function list();

    public function sort($vlogs);

}
