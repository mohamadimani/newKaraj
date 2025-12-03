<?php

namespace App\Enums\OnlinePayment;

use App\Enums\BaseEnum;

enum StatusEnum: string
{
    use BaseEnum;

    case PENDING = 'pending';
    case PAID = 'paid';
    case CANCELED = 'canceled';

    public function getLabel(): string
    {
        return __("payments.status_{$this->value}");
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'primary',
            self::PAID => 'success',
            self::CANCELED => 'danger',
        };
    }
}
