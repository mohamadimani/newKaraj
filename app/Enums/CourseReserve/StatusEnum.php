<?php

namespace App\Enums\CourseReserve;

use App\Enums\BaseEnum;

enum StatusEnum: string
{
    use BaseEnum;

    case PENDING = 'pending';
    case REGISTERED = 'registered';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return __("course_reserves.status_{$this->value}");
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'info',
            self::REGISTERED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
