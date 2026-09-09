<?php

namespace App\Http\Requests\Admin\Marketing;

use App\DTOs\Marketing\MarketingReportDto;
use Illuminate\Foundation\Http\FormRequest;

class MarketingReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "fromDate" => ["nullable", "date"],
            "toDate" => ["nullable", "date"],
            "limit" => ["nullable", "integer", "min:1", "max:" . MarketingReportDto::MAX_LIMIT],
        ];
    }

    /**
     * دیت‌پیکر پنل برای «بدون فیلتر» رشته خالی می‌فرستد؛ قانون date روی رشته خالی خطا می‌دهد،
     * پس قبل از اعتبارسنجی به null تبدیل می‌شود تا بازه‌ی پیش‌فرض اعمال شود.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            "fromDate" => $this->filled("fromDate") ? $this->input("fromDate") : null,
            "toDate" => $this->filled("toDate") ? $this->input("toDate") : null,
            "limit" => $this->filled("limit") ? $this->input("limit") : null,
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
