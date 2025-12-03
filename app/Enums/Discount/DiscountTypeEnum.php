<?php

namespace App\Enums\Discount;

use App\Enums\BaseEnum;

enum DiscountTypeEnum: string
{
    use BaseEnum;

    case PUBLIC = 'public';
    case PROFESSION = 'profession';
    case COURSE = 'course';
    case COURSE_ONLINE = 'course_online';
    case USER = 'user';

    public function name(): string
    {
        return __('discounts.discount_type_' . $this->value);
    }

    public function color(): string
    {
        return match ($this) {
            self::PUBLIC => 'primary',
            self::PROFESSION => 'primary',
            self::USER => 'primary',
            self::COURSE => 'primary',
            self::COURSE_ONLINE => 'primary',
        };
    }
}

