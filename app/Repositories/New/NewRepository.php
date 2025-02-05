<?php

namespace App\Repositories\New;

use App\Models\News;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class NewRepository extends BaseRepository implements NewRepositoryInterface
{
    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    public function findByUrl($url)
    {
        return $this->model::published()->where("url", $url)->first();
    }

    public function activePaginate()
    {
        return $this->model::published()->latest("id")->paginate($this->pageSize);
    }

    public function dataTable()
    {
        return QueryBuilder::for(News::class)
            ->select("news.*")
            ->allowedFilters(['title', 'url', 'content', 'id', 'created_at', 'published'])
            ->allowedSorts(['title', 'url', 'content', 'id', 'created_at', 'published'])
            ->paginate($this->pageSize);
    }

    public function createNews($title, $url, $content, $image, $published)
    {
        return $this->create([
            "title" => $title,
            "url" => $url,
            "content" => $content,
            "image" => $image,
            "published" => $published,
        ]);
    }

    public function updateNews(News $news, $title, $url, $content, $image, $published)
    {
        $news->update([
            "title" => $title,
            "url" => $url,
            "content" => $content,
            "image" => $image,
            "published" => $published,
        ]);
    }
    public function getLastActiveNews()
    {
        return $this->model::published()->latest("id")->limit(4)->get();
    }
    public function getSitemapData()
    {
        return $this->model::published()->select("url")->latest("id")->get();
    }

    public function getLastPost()
    {
        return $this->model::published()->latest("id")->limit(5)->get();
    }
}
