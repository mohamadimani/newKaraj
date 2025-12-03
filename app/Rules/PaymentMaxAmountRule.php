<?php

namespace App\Rules;

use App\Models\CourseRegister;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PaymentMaxAmountRule implements ValidationRule
{
    public function __construct(private int $paymentableId, private string $paymentableType) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $paymentable = $this->paymentableType::find($this->paymentableId);
        if (
            $this->paymentableType == CourseRegister::class
            && ($paymentable->course->price - $paymentable->paid_amount) < $value
        ) {
            $fail(__('payments.messages.max_amount'));
        }
    }
}
