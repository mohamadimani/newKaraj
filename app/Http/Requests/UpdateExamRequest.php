<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "profession_id" => "required|array",
            "branch_id" => "required",
            "title" => "required|string",
            "description" => "required|string|min:5",
            "duration_min" => "required|numeric",
            "passing_score" => "required|numeric",
            "question_count" => "required|numeric",
        ];
    }
}
