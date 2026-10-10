<?php

namespace App\Services\InstagramPost;

use App\DTOs\InstagramPost\InstagramPostMediaDto;
use App\DTOs\InstagramPost\InstagramPostSortDto;
use App\DTOs\InstagramPost\InstagramPostSortMediaDto;
use App\DTOs\InstagramPost\InstagramPostStoreDto;
use App\DTOs\InstagramPost\InstagramPostUpdateDto;
use App\Enums\InstagramMediaType;
use App\Models\InstagramPost;
use App\Repositories\InstagramPost\InstagramPostRepositoryInterface;
use App\Repositories\InstagramPostMedia\InstagramPostMediaRepositoryInterface;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class InstagramPostService implements InstagramPostServiceInterface
{
    /** پوشه‌ی S3 مشترک همه‌ی رسانه‌های این بخش */
    private const DISK_FOLDER = "instagram";

    public function __construct(
        private InstagramPostRepositoryInterface      $postRepository,
        private InstagramPostMediaRepositoryInterface $mediaRepository,
        private S3ServiceInterface                    $s3Service,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->postRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $post = $this->postRepository->findWithMedia($id);
        if (!$post) {
            throw new NotFoundHttpException();
        }
        return $post;
    }

    /**
     * پست همراه با همه‌ی فایل‌هایش در یک درخواست ثبت می‌شود؛
     * پستِ بدون رسانه در فید چیزی برای نمایش ندارد.
     */
    public function store(InstagramPostStoreDto $dto): mixed
    {
        $post = $this->postRepository->create([
            "caption" => $dto->caption,
            "status" => $dto->status,
            "author" => $dto->author,
        ]);

        $this->storeMediaFiles($post->id, $dto->media, 0, $dto->covers);

        return $this->find($post->id);
    }

    public function update(InstagramPostUpdateDto $dto): mixed
    {
        $post = $this->find($dto->postId);

        $this->postRepository->update($post, [
            "caption" => $dto->caption,
            "status" => $dto->status,
        ]);

        return $post;
    }

    /** حذف پست، همراه با پاک‌کردن فایل‌هایش از S3 تا آبجکت یتیم نماند */
    public function destroy(int $id): bool|null
    {
        $post = $this->find($id);

        foreach ($post->media as $media) {
            $this->s3Service->remove(self::DISK_FOLDER . "/" . $media->file);
            if ($media->cover) {
                $this->s3Service->remove(self::DISK_FOLDER . "/" . $media->cover);
            }
        }

        $this->mediaRepository->deleteByPost($post->id);

        return $this->postRepository->delete($post);
    }

    public function sort(InstagramPostSortDto $dto): bool
    {
        foreach ($dto->post as $item) {
            $this->postRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function findActive(int $id): mixed
    {
        $post = $this->postRepository->findActiveWithMedia($id);
        if (!$post) {
            throw new NotFoundHttpException();
        }
        return $post;
    }

    public function listing($filters): mixed
    {
        $query = $this->postRepository->activeQuery();
        $query = $this->renderFilter($query, $filters);
        return $this->postRepository->paginated($query);
    }

    public function list(): mixed
    {
        return $this->postRepository->activeList();
    }

    public function view(InstagramPost $post): mixed
    {
        return $this->postRepository->update($post, ["view" => $post->view + 1]);
    }

    public function getMedia(int $postId): mixed
    {
        return $this->mediaRepository->getByPost($postId);
    }

    public function addMedia(InstagramPostMediaDto $dto): mixed
    {
        $post = $this->find($dto->postId);

        return $this->storeMediaFiles($post->id, $dto->media, $this->mediaRepository->nextSort($post->id), $dto->covers);
    }

    public function removeMedia(int $id): bool|null
    {
        $media = $this->mediaRepository->findOrFail($id);
        $this->s3Service->remove(self::DISK_FOLDER . "/" . $media->file);
        if ($media->cover) {
            $this->s3Service->remove(self::DISK_FOLDER . "/" . $media->cover);
        }
        return $this->mediaRepository->delete($media);
    }

    public function updateCover(int $id, UploadedFile $cover): mixed
    {
        $media = $this->mediaRepository->findOrFail($id);
        if ($media->type !== InstagramMediaType::Video->value) {
            throw ValidationException::withMessages(['cover' => 'کاور فقط برای ویدیو قابل ثبت است.']);
        }

        $previous = $media->cover;
        $fileName = $this->s3Service->upload($cover, self::DISK_FOLDER);

        try {
            if (!$this->mediaRepository->update($media, ['cover' => $fileName])) {
                throw new \RuntimeException('Unable to save Instagram video cover.');
            }
        } catch (\Throwable $exception) {
            $this->s3Service->remove(self::DISK_FOLDER . '/' . $fileName);
            throw $exception;
        }

        if ($previous && $previous !== $fileName) {
            $this->s3Service->remove(self::DISK_FOLDER . '/' . $previous);
        }

        return $media;
    }

    public function sortMedia(InstagramPostSortMediaDto $dto): bool
    {
        foreach ($dto->media as $item) {
            $this->mediaRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    /* ------------------------------------------------------------------ */

    private function renderFilter($query, $filters)
    {
        if ($filters) {
            foreach ($filters as $filter => $value) {
                if ($filter == "search") {
                    /** Example : filter[search]=قهوه ساز */
                    $query = $this->postRepository->filterCaption($query, $value);
                }
            }
        }
        return $query;
    }

    /**
     * فایل‌ها به ترتیبی که ادمین انتخاب کرده روی S3 می‌روند و همان ترتیب
     * در ستون sort ذخیره می‌شود؛ کاروسل دقیقاً به همان ترتیب پخش می‌شود.
     *
     * @param \Illuminate\Http\UploadedFile[] $files
     */
    private function storeMediaFiles(int $postId, array $files, int $startSort, array $covers = []): array
    {
        $created = [];

        foreach ($files as $index => $file) {
            $type = InstagramMediaType::fromMime($file->getMimeType());
            $fileName = $this->s3Service->upload($file, self::DISK_FOLDER);
            $coverName = null;

            try {
                if ($type === InstagramMediaType::Video && isset($covers[$index])) {
                    $coverName = $this->s3Service->upload($covers[$index], self::DISK_FOLDER);
                }

                $created[] = $this->mediaRepository->create([
                    "instagram_post_id" => $postId,
                    "type" => $type->value,
                    "file" => $fileName,
                    "cover" => $coverName,
                    "sort" => $startSort + count($created),
                ]);
            } catch (\Throwable $exception) {
                $this->s3Service->remove(self::DISK_FOLDER . '/' . $fileName);
                if ($coverName) {
                    $this->s3Service->remove(self::DISK_FOLDER . '/' . $coverName);
                }
                throw $exception;
            }
        }

        return $created;
    }
}
