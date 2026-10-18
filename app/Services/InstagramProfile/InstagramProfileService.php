<?php

namespace App\Services\InstagramProfile;

use App\DTOs\InstagramProfile\InstagramProfileUpdateDto;
use App\Models\InstagramProfile;
use App\Repositories\InstagramProfile\InstagramProfileRepositoryInterface;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

readonly class InstagramProfileService implements InstagramProfileServiceInterface
{
    private const DISK_FOLDER = 'instagram';

    public function __construct(
        private InstagramProfileRepositoryInterface $repository,
        private S3ServiceInterface $storage,
    ) {}

    public function get(): InstagramProfile
    {
        return $this->repository->get();
    }

    public function update(InstagramProfileUpdateDto $dto): InstagramProfile
    {
        $profile = $this->get();
        $previous = $profile->avatar;
        $uploaded = $dto->avatar
            ? $this->storage->upload($dto->avatar, self::DISK_FOLDER, Str::uuid().'.'.$dto->avatar->extension())
            : null;

        try {
            if (! $this->repository->save($profile, [
                'name' => $dto->name,
                'url' => $dto->url,
                'bio' => $dto->bio,
                'avatar' => $uploaded ?? ($dto->remove_avatar ? null : $previous),
            ])) {
                throw new \RuntimeException('Unable to save Instagram profile.');
            }
        } catch (\Throwable $exception) {
            if ($uploaded) {
                $this->removeFile($uploaded);
            }
            throw $exception;
        }

        if ($previous && $previous !== $profile->avatar) {
            // The request middleware owns the transaction; keep the old photo until commit.
            DB::afterCommit(fn () => $this->removeFile($previous));
        }

        return $profile;
    }

    private function removeFile(string $file): void
    {
        try {
            $this->storage->remove(self::DISK_FOLDER.'/'.$file);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
