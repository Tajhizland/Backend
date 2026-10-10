<?php

namespace App\Services\InstagramPost;

use App\DTOs\InstagramPost\InstagramPostMediaDto;
use App\DTOs\InstagramPost\InstagramPostSortDto;
use App\DTOs\InstagramPost\InstagramPostSortMediaDto;
use App\DTOs\InstagramPost\InstagramPostStoreDto;
use App\DTOs\InstagramPost\InstagramPostUpdateDto;
use App\Models\InstagramPost;
use Illuminate\Http\UploadedFile;

interface InstagramPostServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function store(InstagramPostStoreDto $dto): mixed;

    public function update(InstagramPostUpdateDto $dto): mixed;

    public function destroy(int $id): bool|null;

    public function sort(InstagramPostSortDto $dto): bool;

    public function findActive(int $id): mixed;

    public function listing($filters): mixed;

    public function list(): mixed;

    public function view(InstagramPost $post): mixed;

    public function getMedia(int $postId): mixed;

    public function addMedia(InstagramPostMediaDto $dto): mixed;

    public function removeMedia(int $id): bool|null;

    public function updateCover(int $id, UploadedFile $cover): mixed;

    public function sortMedia(InstagramPostSortMediaDto $dto): bool;
}
