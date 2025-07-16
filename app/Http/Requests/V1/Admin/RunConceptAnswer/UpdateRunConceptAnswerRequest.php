<?php

namespace App\Http\Requests\V1\Admin\RunConceptAnswer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRunConceptAnswerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:run_concept_answers'],
            'run_concept_question_id' => ['required', 'exists:run_concept_questions,id'],
            'answer' => ['required'],
            'status' => ['required', 'integer'],
            'price' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
