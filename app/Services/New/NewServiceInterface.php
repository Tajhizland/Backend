<?php

namespace App\Services\New;

interface NewServiceInterface
{
    public function findByUrl($url);

    public function findById($id);

    public function activePaginate($filters);

    public function dataTable();

    public function storeNews($title, $url, $content, $image, $published, $categoryId, $author);

    public function updateNews($id, $title, $url, $content, $image, $published, $categoryId);

    public function getSitemapData();

    public function getLastPost();
}
