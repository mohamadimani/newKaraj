<?php

namespace App\Enums\Discount;

use App\Enums\BaseEnum;

enum AmountTypeEnum: string
{
    use BaseEnum;

    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';

    public function name(): string
    {
        return __('discounts.amount_type_' . $this->value);
    }

    public function color(): string
    {
        return match ($this) {
            self::PERCENTAGE => 'warning',
            self::FIXED => 'primary',
        };
    }
}
