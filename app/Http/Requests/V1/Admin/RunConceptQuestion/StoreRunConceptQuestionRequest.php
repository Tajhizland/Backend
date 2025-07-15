<?php

namespace App\Http\Requests\V1\Admin\RunConceptQuestion;

use Illuminate\Foundation\Http\FormRequest;

class StoreRunConceptQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question' => ['required'],
            'parent_question' => ['nullable', 'exists:run_concept_questions,id'],
            'parent_answer' => ['nullable', 'exists:run_concept_answers,id'],
            'status' => ['required', 'integer'],
            'level' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
