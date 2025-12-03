<?php

namespace App\Http\Requests\Admin;

use App\Rules\PaymentMaxAmountRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
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
        $validate = [
            'paymentable_id' => 'required|numeric',
            'paymentable_type' => 'required|string',
            'payment_method_id' => 'required|numeric',
            'pay_date' => 'required',
            'payment_description' => 'nullable|string',
            'paid_amount' => ['required', 'numeric', 'min:1'],
            'paid_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2000',
        ];

        if ($this->paymentable_id and  $this->paymentable_type) {
            $validate = $validate + [
                'paid_amount' => ['required', 'numeric', 'min:1', new PaymentMaxAmountRule($this->paymentable_id, $this->paymentable_type)],
            ];
        }
        return $validate;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'paid_amount' => floatval(str_replace(',', '', $this->paid_amount)),
        ]);
    }
}
