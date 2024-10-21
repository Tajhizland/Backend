<?php

namespace App\Jobs;

use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Services\Notification\NotificationService;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private $productId, private $file, private $type)
    {
    }

    public function handle(ProductServiceInterface $productService , NotificationRepositoryInterface $notificationRepository): void
    {
        try {
            $file = base64_decode($this->file);
            $productService->setVideo($this->productId, $file, $this->type);
            $title=" آپلود ویدیو";
            $message="ویدیوی محصول $this->productId با موفقیت آپلود شد  ";
            $link="/admin/product/video/$this->productId";
            $type="success";
            $notificationRepository->createNotification($title, $message, $link, $type);
        } catch (\Throwable $throwable) {
            $title="خطا در آپلود ویدیو";
            $message="ویدیوی محصول $this->productId به دلیل بروز خطا آپلود نشد ! ";
            $link="/admin/product/video/$this->productId";
            $type="error";
            $notificationRepository->createNotification($title, $message, $link, $type);
        }
    }
}
