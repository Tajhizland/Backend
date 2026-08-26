<?php

namespace App\Services\Vlog;

use App\Enums\VideoStatus;
use App\Jobs\ConvertVideoToHlsJob;
use App\Models\Vlog;
use App\DTOs\Vlog\VlogSortDto;
use App\DTOs\Vlog\VlogStoreDirectDto;
use App\DTOs\Vlog\VlogStoreDto;
use App\DTOs\Vlog\VlogUpdateDto;
use App\Repositories\Vlog\VlogRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repositories\VlogCategory\VlogCategoryRepositoryInterface;
use App\Services\ConvertToHLS\HlsServiceInterface;
use App\Services\DirectUpload\DirectUploadServiceInterface;
use App\Services\S3\S3ServiceInterface;

readonly class VlogService implements VlogServiceInterface
{
    public function __construct
    (
        private VlogRepositoryInterface         $vlogRepository,
        private VlogCategoryRepositoryInterface $vlogCategoryRepository,
        private S3ServiceInterface              $s3Service,
        private HlsServiceInterface             $hlsService,
        private DirectUploadServiceInterface    $directUploadService,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->vlogRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $vlog = $this->vlogRepository->find($id);
        if (!$vlog) {
            throw new NotFoundHttpException();
        }
        return $vlog;
    }

//    public function store($title, $description, $video, $poster, $url, $status, $categoryId, $author)
//    {
//        $hlsPath=$this->hlsService->convertAndUploadS3($video);
////        $filePath = $this->s3Service->upload($video, "vlog");
//        $posterPath = $this->s3Service->upload($poster, "vlog");
//        return $this->vlogRepository->create([
//            "title" => $title,
//            "description" => $description,
//            "video" => " ",
//            "poster" => $posterPath,
//            "status" => $status,
//            "url" => $url,
//            "hls" => $hlsPath,
//            "category_id" => $categoryId,
//            "author" => $author,
//        ]);
//    }
    public function store(VlogStoreDto $dto): mixed
    {
        $filePath = $this->s3Service->upload($dto->video, "vlog");
        $posterPath = $this->s3Service->upload($dto->poster, "vlog");
        $vlog = $this->vlogRepository->create([
            "title" => $dto->title,
            "description" => $dto->description,
            "video" => $filePath,
            "poster" => $posterPath,
            "status" => $dto->status,
            "url" => $dto->url,
            "category_id" => $dto->categoryId,
            "author" => $dto->author,
            "video_status" => VideoStatus::Queued->value,
        ]);
        ConvertVideoToHlsJob::dispatch($vlog);
        return $vlog;
    }

    /**
     * ثبت ولاگ برای ویدیویی که کلاینت مستقیماً روی S3 آپلود کرده است.
     * فقط کلیدِ فایل دریافت می‌شود؛ هیچ بایتی از این سرور عبور نمی‌کند.
     */
    public function storeDirect(VlogStoreDirectDto $dto): mixed
    {
        // پوستر اول آپلود می‌شود تا اگر شکست خورد، ویدیو هنوز در مسیر موقت
        // باشد و توسط upload:prune جمع شود (نه اینکه در پوشه‌ی نهایی یتیم بماند)
        $posterPath = $this->s3Service->upload($dto->poster, "vlog");

        // انتقال از مسیر موقت به پوشه‌ی نهایی و تأیید مالکیت کلید
        $fileName = $this->directUploadService->consume($dto->videoKey, $dto->author);

        $vlog = $this->vlogRepository->create([
            "title" => $dto->title,
            "description" => $dto->description,
            "video" => $fileName,
            "poster" => $posterPath,
            "status" => $dto->status,
            "url" => $dto->url,
            "category_id" => $dto->categoryId,
            "author" => $dto->author,
            "video_status" => VideoStatus::Queued->value,
        ]);

        ConvertVideoToHlsJob::dispatch($vlog);

        return $vlog;
    }

    public function update(VlogUpdateDto $dto): mixed
    {
        $vlog = $this->find($dto->vlogId);
        $filePath = $vlog->video;
        $posterPath = $vlog->poster;
        if (isset($dto->video)) {
            $this->s3Service->remove("vlog/" . $filePath);
            $filePath = $this->s3Service->upload($dto->video, "vlog");
        }
        if (isset($dto->poster)) {
            $this->s3Service->remove("vlog/" . $posterPath);
            $posterPath = $this->s3Service->upload($dto->poster, "vlog");
        }
        $this->vlogRepository->update($vlog, [
            "title" => $dto->title,
            "description" => $dto->description,
            "video" => $filePath,
            "poster" => $posterPath,
            "url" => $dto->url,
            "status" => $dto->status,
            "category_id" => $dto->categoryId,
        ]);

        // فقط وقتی ویدیوی جدیدی آمده ترنسکد دوباره انجام می‌شود؛
        // ویرایش عنوان نباید کل ویدیو را دوباره پردازش کند
        if (isset($dto->video)) {
            $this->vlogRepository->update($vlog, ["video_status" => VideoStatus::Queued->value]);
            ConvertVideoToHlsJob::dispatch($vlog);
        }

        return $vlog;
    }
//   public function update($id, $title, $description, $video, $poster, $url, $status, $categoryId)
//    {
//        $vlog = $this->vlogRepository->findOrFail($id);
//        $filePath = $vlog->video;
//        $hlsPath = $vlog->hls;
//        $posterPath = $vlog->poster;
//        if (isset($video)) {
//            $this->s3Service->remove("vlog/" . $filePath);
//            $this->s3Service->removeFolder("hls/".$hlsPath);
//            $hlsPath=$this->hlsService->convertAndUploadS3($video);
////            $filePath = $this->s3Service->upload($video, "vlog");
//        }
//        if (isset($poster)) {
//            $this->s3Service->remove("vlog/" . $posterPath);
//            $posterPath = $this->s3Service->upload($poster, "vlog");
//        }
//        $this->vlogRepository->update($vlog, [
//            "title" => $title,
//            "description" => $description,
//            "video" => $filePath,
//            "poster" => $posterPath,
//            "url" => $url,
//            "hls" => $hlsPath,
//            "status" => $status,
//            "category_id" => $categoryId,
//        ]);
//    }

    public function findByUrl($url): mixed
    {
        $vlog = $this->vlogRepository->findByUrl($url);
        if (!$vlog)
            throw new NotFoundHttpException();
        return $vlog;
    }

    public function listing($filters): mixed
    {
        $vlogQuery = $this->vlogRepository->activeVlogQuery();
        $vlogQuery = $this->renderFilter($vlogQuery, $filters);
        return $this->vlogRepository->paginated($vlogQuery);
    }

    private function renderFilter($vlogQuery, $filters)
    {
        if ($filters) {
            foreach ($filters as $filter => $value) {
                if ($filter == "category") {
                    /** Example : filter[category]=10 */
                    $vlogQuery = $this->vlogRepository->filterCategory($vlogQuery, $value);
                }
                if ($filter == "search") {
                    /** Example : filter[search]=10 */
                    $vlogQuery = $this->vlogRepository->filterTitle($vlogQuery, $value);
                }
                if ($filter == "sort") {
                    /** Example : filter[sort]=10 */
                    if ($value == "view")
                        $vlogQuery = $this->vlogRepository->sortView($vlogQuery);
                    if ($value == "new")
                        $vlogQuery = $this->vlogRepository->sortNew($vlogQuery);
                    if ($value == "old")
                        $vlogQuery = $this->vlogRepository->sortOld($vlogQuery);
                }
            }
        }
        return $vlogQuery;
    }

    public function getRelatedVlogs($category_id, $except): mixed
    {
        return $this->vlogRepository->getRelatedVlogs($category_id, $except);
    }

    public function view(Vlog $vlog): mixed
    {
        return $this->vlogRepository->update($vlog, ["view" => $vlog->view + 1]);
    }

    public function getSitemapData(): mixed
    {
        return $this->vlogRepository->getSitemapData();
    }

    public function getMostViewed(): mixed
    {
        return $this->vlogRepository->getMostViewed();
    }

    public function search($query): mixed
    {
        return $this->vlogRepository->search($query);
    }

    public function sort(VlogSortDto $dto): bool
    {
        foreach ($dto->vlog as $item) {
            $this->vlogRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function list(): mixed
    {
        return $this->vlogRepository->activeList();
    }

    public function getByCategoryUrl($url , $filters): mixed
    {
        $category = $this->vlogCategoryRepository->findByUrl($url);
        $vlogQuery=$this->vlogRepository->getByCategoryQuery($category->id);
        $vlogQuery = $this->renderFilter($vlogQuery, $filters);
        return $this->vlogRepository->paginated($vlogQuery);
    }
}
