<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'address' => 'nullable|string',
            'site' => 'nullable|string',
            'bank_card_number' => 'nullable|string',
            'bank_card_name' => 'nullable|string',
            'bank_card_owner' => 'nullable|string',
            'minimum_pay' => 'nullable|string',
            'online_pay_link' => 'nullable|string',
            'manager' => 'nullable|string',
            'province_id' => 'required|exists:provinces,id',
        ];
    }
}
