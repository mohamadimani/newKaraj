<?php

namespace App\Http\Requests\Admin\MarketingSms;

use App\Enums\MarketingSms\TargetTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class MarketingSmsTemplateRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'unique:marketing_sms_templates,title,' . ($this->marketingSmsTemplate->id ?? 'NULL') . ',id,deleted_at,NULL',
            ],
            'target_type' => ['required', 'string', 'in:' . TargetTypeEnum::implodedValues()],
            'branch_id' => ['required', 'exists:branches,id'],
            'profession_ids' => ['required', 'array', 'exists:professions,id'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->is_active === 'on' ? true : false,
        ]);
    }
}
