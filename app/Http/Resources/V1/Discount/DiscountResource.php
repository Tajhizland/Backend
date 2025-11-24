<?php

namespace App\Http\Resources\V1\Discount;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin Discount */
class DiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,


            'start_date_fa' => $this->start_date != null ? Jalalian::fromDateTime($this->start_date)->format('Y/m/d H:i') : "",
            'end_date_fa' => $this->end_date != null ? Jalalian::fromDateTime($this->end_date)->format('Y/m/d H:i') : "",

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
