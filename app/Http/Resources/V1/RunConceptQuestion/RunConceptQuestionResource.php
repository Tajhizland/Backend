<?php

namespace App\Http\Resources\V1\RunConceptQuestion;

use App\Http\Resources\V1\RunConceptAnswer\RunConceptAnswerResource;
use App\Models\RunConceptQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RunConceptQuestion */
class RunConceptQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'status' => $this->status,
            'level' => $this->level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'parent_question' => $this->parent_question,
            'parent_answer' => $this->parent_answer,

            'parentQuestion' => new RunConceptQuestionResource($this->whenLoaded('parentQuestion')),
            'parentAnswer' => new RunConceptAnswerResource($this->whenLoaded('parentAnswer')),
        ];
    }
}
