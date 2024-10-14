<?php

namespace App\Services\FileManager;

interface FileManagerServiceInterface
{
    public function geyByModelId($modelId ,$modelType);
    public function remove($id);
    public function upload($file,  $modelType, $modelId);
}
