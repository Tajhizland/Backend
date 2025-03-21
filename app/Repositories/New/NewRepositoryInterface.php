<?php

namespace App\Repositories\New;

use App\Models\News;
use App\Repositories\Base\BaseRepositoryInterface;

interface NewRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUrl($url);
    public function paginated($blogQuery);
    public function filterCategory($blogQuery ,$category);
    public function activePaginateQuery();
    public function getLastActiveNews();
    public function dataTable();
    public function createNews($title,$url,$content,$image,$published);
    public function updateNews(News $news,$title,$url,$content,$image,$published);
    public function getSitemapData();
    public function getLastPost();

}
