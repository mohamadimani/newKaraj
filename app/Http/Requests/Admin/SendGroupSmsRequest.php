<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SendGroupSmsRequest extends FormRequest
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
            'sms_message' => ['required', 'string'],
            'receivers' => ['required', 'array', 'exists:users,id'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'receivers' => explode(',', $this->receivers),
        ]);
    }
}
