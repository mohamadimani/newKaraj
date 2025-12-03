<?php

namespace App\Enums\CourseRegister;

use App\Enums\BaseEnum;

enum StatusEnum: string
{
    use BaseEnum;

    case REGISTERED = 'registered';
    case COMPLETED = 'completed';
    case TECHNICAL = 'technical';
    case CANCELLED = 'cancelled';
    case RESERVED = 'reserved';

    public function getLabel(): string
    {
        return __('course_registers.status_' . $this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::REGISTERED => 'primary',
            self::COMPLETED => 'primary',
            self::TECHNICAL => 'primary',
            self::CANCELLED => 'danger',
            self::RESERVED => 'warning',
        };
    }
}
