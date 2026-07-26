<?php

namespace App\Enums;

enum DirectUploadStatus: string
{
    /** URL امضا شده، آپلود هنوز تمام نشده */
    case Pending = 'pending';

    /** آپلود تمام شد و آبجکت روی S3 تأیید شد؛ آماده‌ی مصرف در فرم */
    case Completed = 'completed';

    /** به مسیر نهایی منتقل و به یک رکورد (مثلاً ولاگ) وصل شد */
    case Consumed = 'consumed';

    /** توسط کاربر لغو یا توسط upload:prune پاک شد */
    case Aborted = 'aborted';

    public function label(): string
    {
        return match ($this) {
            static::Pending => 'در حال آپلود',
            static::Completed => 'آپلود شده',
            static::Consumed => 'استفاده شده',
            static::Aborted => 'لغو شده',
        };
    }
}
