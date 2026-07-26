<?php

namespace App\Repositories\DirectUpload;

use App\Models\DirectUpload;

interface DirectUploadRepositoryInterface
{
    public function create(array $data): mixed;

    public function update(\Illuminate\Database\Eloquent\Model $entity, array $data): bool;

    public function findByKey(string $key): ?DirectUpload;

    /** رکورد متعلق به همین کاربر؛ جلوی استفاده از کلید دیگران را می‌گیرد */
    public function findOwned(string $key, $userId): ?DirectUpload;

    /** آپلودهای ناتمامی که باید پاک شوند */
    public function stale(int $hours);
}
