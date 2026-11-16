<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTreeParent implements ValidationRule
{
    public function __construct(private string $model, private int $currentId = 0) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parentId = (int) $value;
        $visited = [];
        while ($parentId !== 0) {
            if ($parentId === $this->currentId || isset($visited[$parentId])) {
                $fail('انتخاب خود آیتم یا زیرمجموعه آن به عنوان والد مجاز نیست.');
                return;
            }
            $visited[$parentId] = true;
            $parent = $this->model::find($parentId);
            if (!$parent) {
                $fail('والد انتخاب‌شده وجود ندارد.');
                return;
            }
            $parentId = (int) $parent->parent_id;
        }
    }
}
