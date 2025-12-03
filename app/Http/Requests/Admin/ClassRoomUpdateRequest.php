<?php

namespace App\Http\Requests\Admin;

use App\Rules\ClassRoomNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProfessionCapacityRule;

class ClassRoomUpdateRequest extends FormRequest
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
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255|unique:class_rooms,name,' . $this->classRoom->id . ',id,deleted_at,NULL,is_active,1',
            'number' => ['required', 'string', 'max:255', new ClassRoomNumberRule($this->number, $this->branch_id, $this->classRoom->id)],
            'profession_id' => ['required', 'array', new ProfessionCapacityRule($this->capacity)],
            'profession_id.*' => 'required|exists:professions,id',
            'capacity' => 'required|integer|min:1',
        ];
    }
}
