<?php

namespace App\Services\New;

interface NewServiceInterface
{
    public function findByUrl($url);
    public function findById($id);
    public function activePaginate();
    public function dataTable();
    public function storeNews($title,$url,$content,$image,$published,$author);
    public function updateNews($id,$title,$url,$content,$image,$published);
    public function getSitemapData();
}
