<?php

namespace App\Repositories\FileManager;

use App\Models\FileManager;
use App\Repositories\Base\BaseRepository;

class FileManagerRepository extends BaseRepository implements FileManagerRepositoryInterface
{
    public function __construct(FileManager $model)
    {
        parent::__construct($model);
    }

    public function geyByModelId($modelId,$modelType)
    {
        return $this->model::where("model_id", $modelId)->where("model_type", $modelType)->get();
    }

    public function store($path, $type, $model_type, $model_id)
    {
        return $this->create([
            "path" => $path,
            "type" => $type,
            "model_type" => $model_type,
            "model_id" => $model_id
        ]);
    }

}
