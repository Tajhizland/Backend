<?php

namespace App\Repositories\FileManager;

use App\Repositories\Base\BaseRepositoryInterface;

interface FileManagerRepositoryInterface extends  BaseRepositoryInterface
{
    public function geyByModelId($modelId ,$modelType);
    public function store( $path, $model_type, $model_id );
}
