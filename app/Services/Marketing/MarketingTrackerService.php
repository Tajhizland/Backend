<?php

namespace App\Services\Marketing;

use App\DTOs\Marketing\MarketingEventDto;
use App\Enums\MarketingEventType;
use App\Repositories\MarketingEvent\MarketingEventRepositoryInterface;
use App\Repositories\SearchLog\SearchLogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * مسیر نوشتنِ داده‌های مارکتینگ.
 *
 * هر ثبت در try/catch است: آمار نباید هیچ‌وقت باعث خطای صفحه محصول یا سبد خرید شود.
 * زمینه‌ی درخواست (کاربر، آی‌پی، شناسه نشست) خودِ سرویس از request می‌خواند تا فراخوان‌ها
 * در سرویس‌های موجود یک خط بمانند.
 */
readonly class MarketingTrackerService implements MarketingTrackerServiceInterface
{
    private const SESSION_HEADER = 'X-Session-Id';

    /** عبارت‌های تک‌حرفی سیگنال مارکتینگی ندارند. */
    private const MIN_TERM_LENGTH = 2;

    /** بازه‌ای که چند درخواستِ پشت‌سرهم، یک جستجوی واحد در نظر گرفته می‌شوند. */
    private const TYPING_WINDOW_SECONDS = 60;

    public function __construct(
        private MarketingEventRepositoryInterface $marketingEventRepository,
        private SearchLogRepositoryInterface      $searchLogRepository,
    )
    {
    }

    public function track(MarketingEventType $type, ?int $productId = null, array $attributes = []): void
    {
        try {
            $this->marketingEventRepository->record([
                'type' => $type->value,
                'product_id' => $productId,
                'category_id' => $attributes['category_id'] ?? null,
                'user_id' => $attributes['user_id'] ?? $this->currentUserId(),
                'ip' => request()->ip(),
                'session_id' => $this->sessionId(),
                'quantity' => max((int)($attributes['quantity'] ?? 1), 1),
                'meta' => $attributes['meta'] ?? null,
            ]);
        } catch (Throwable $exception) {
            Log::warning('marketing event tracking failed', [
                'type' => $type->value,
                'product_id' => $productId,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * رویدادهایی که فقط سمت مرورگر اتفاق می‌افتند (سبد مهمان، مقایسه) از اینجا ثبت می‌شوند.
     * نوع غیرمجاز بی‌سروصدا نادیده گرفته می‌شود تا اندپوینت عمومی قابل سوءاستفاده نباشد.
     */
    public function trackFromClient(MarketingEventDto $dto): void
    {
        $type = MarketingEventType::tryFrom($dto->type);

        if (!$type || !in_array($dto->type, MarketingEventType::clientTrackable(), true)) {
            return;
        }

        $this->track($type, $dto->product_id, [
            'category_id' => $dto->category_id,
            'quantity' => $dto->quantity ?? 1,
            'meta' => $dto->meta,
        ]);
    }

    /**
     * جستجوی هدر با هر بار تایپ (debounce) صدا زده می‌شود، پس ثبت خام باعث می‌شد گزارش پر شود از
     * «ی»، «یخ»، «یخچ». اینجا اگر جستجوی قبلیِ همین بازدیدکننده پیشوند عبارت جدید باشد، همان ردیف
     * کامل می‌شود و اگر عبارت جدید پیشوند قبلی باشد (بک‌اسپیس) اصلا ثبت نمی‌شود.
     */
    public function logSearch(string $term, int $resultCount, ?int $productCount = null, string $source = 'shop'): void
    {
        $normalized = $this->normalizeTerm($term);

        if (mb_strlen($normalized) < self::MIN_TERM_LENGTH) {
            return;
        }

        $payload = [
            'term' => mb_substr(trim($term), 0, 255),
            'normalized_term' => mb_substr($normalized, 0, 191),
            'result_count' => max($resultCount, 0),
            'product_count' => max($productCount ?? $resultCount, 0),
            'user_id' => $this->currentUserId(),
            'ip' => request()->ip(),
            'session_id' => $this->sessionId(),
            'source' => $source,
        ];

        try {
            $previous = $this->findTypingAncestor($payload['normalized_term']);

            if ($previous === false) {
                return;
            }

            $previous
                ? $this->searchLogRepository->update($previous, $payload)
                : $this->searchLogRepository->record($payload);
        } catch (Throwable $exception) {
            Log::warning('search logging failed', [
                'term' => $term,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @return \App\Models\SearchLog|null|false ردیف قابل تکمیل، null برای ثبت جدید،
     *                                            و false یعنی این جستجو تکرارِ ناقصِ جستجوی قبلی است.
     */
    private function findTypingAncestor(string $normalized): mixed
    {
        $recent = $this->searchLogRepository->recentForVisitor(
            $this->sessionId(),
            request()->ip(),
            Carbon::now()->subSeconds(self::TYPING_WINDOW_SECONDS)
        );

        foreach ($recent as $log) {
            if ($log->normalized_term === $normalized) {
                return $log;
            }

            if (str_starts_with($normalized, $log->normalized_term)) {
                return $log;
            }

            if (str_starts_with($log->normalized_term, $normalized)) {
                return false;
            }
        }

        return null;
    }

    /**
     * یکسان‌سازی عبارت جستجو تا «یخچال»، «يخچال» و «یخچال  » یک ردیف در گزارش باشند:
     * حروف عربی به فارسی، حذف نیم‌فاصله و اعراب، و جمع‌کردن فاصله‌های اضافه.
     */
    public function normalizeTerm(string $term): string
    {
        $normalized = mb_strtolower(trim($term));

        $normalized = str_replace(
            ['ي', 'ك', 'ۀ', 'ة', 'أ', 'إ', 'آ', 'ؤ', 'ئ', '‌', '‍', 'ٔ'],
            ['ی', 'ک', 'ه', 'ه', 'ا', 'ا', 'ا', 'و', 'ی', ' ', '', ''],
            $normalized
        );

        $normalized = str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $normalized
        );

        // حذف اعراب عربی
        $normalized = preg_replace('/[\x{064B}-\x{0652}]/u', '', $normalized) ?? $normalized;

        return trim(preg_replace('/\s+/u', ' ', $normalized) ?? $normalized);
    }

    /**
     * مسیرهای فروشگاه (جستجو، رویدادهای مارکتینگ) middleware احراز هویت ندارند، پس گارد پیش‌فرض
     * روی این درخواست‌ها کاربر را برنمی‌گرداند و همه‌چیز «مهمان» ثبت می‌شد. توکن Bearer را
     * مستقیم از گارد sanctum می‌خوانیم تا کاربرِ لاگین‌کرده به رویداد بچسبد.
     */
    private function currentUserId(): ?int
    {
        return Auth::id() ?? Auth::guard('sanctum')->id();
    }

    private function sessionId(): ?string
    {
        $header = request()->header(self::SESSION_HEADER);

        return $header ? mb_substr($header, 0, 64) : null;
    }
}
