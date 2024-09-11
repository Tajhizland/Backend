<?php

namespace App\Services\FileManager;

interface FileManagerServiceInterface
{
    public function geyByModelId($modelId ,$modelType);
    public function upload($file, $path, $type, $modelType, $modelId);
}
