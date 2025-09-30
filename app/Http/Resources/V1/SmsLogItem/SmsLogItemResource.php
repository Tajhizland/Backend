<?php

namespace App\Http\Resources\V1\SmsLogItem;

use App\Http\Resources\V1\SmsLog\SmsLogResource;
use App\Models\SmsLogItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SmsLogItem */
class SmsLogItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mobile' => $this->mobile,
            'message' => $this->message,
            'is_send' => $this->is_send,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'sms_log_id' => $this->sms_log_id,

            'smsLog' => new SmsLogResource($this->whenLoaded('smsLog')),
        ];
    }
}
