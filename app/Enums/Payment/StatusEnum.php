<?php

namespace App\Enums\Payment;

use App\Enums\BaseEnum;

enum StatusEnum: string
{
    use BaseEnum;

    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return __("payments.status_{$this->value}");
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'primary',
            self::VERIFIED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public static function getLabelForLog($value): string
    {
        return '<span class="badge bg-label-' . self::from($value)->getColor() . '">' . __("payments.status_{$value}"). '</span>';
    }
}
