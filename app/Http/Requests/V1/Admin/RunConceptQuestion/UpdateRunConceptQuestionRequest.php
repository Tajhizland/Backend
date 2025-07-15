<?php

namespace App\Http\Requests\V1\Admin\RunConceptQuestion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRunConceptQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['exists:App\Models\RunConceptQuestion'],
            'question' => ['required'],
            'parent_question' => ['nullable', 'exists:run_concept_questions'],
            'parent_answer' => ['nullable', 'exists:run_concept_answers'],
            'status' => ['required', 'integer'],
            'level' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
