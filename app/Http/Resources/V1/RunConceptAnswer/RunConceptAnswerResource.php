<?php

namespace App\Http\Resources\V1\RunConceptAnswer;

use App\Http\Resources\V1\RunConceptQuestion\RunConceptQuestionResource;
use App\Models\RunConceptAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RunConceptAnswer */
class RunConceptAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'answer' => $this->answer,
            'status' => $this->status,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'run_concept_question_id' => $this->run_concept_question_id,

            'runConceptQuestion' => new RunConceptQuestionResource($this->whenLoaded('runConceptQuestion')),
        ];
    }
}
