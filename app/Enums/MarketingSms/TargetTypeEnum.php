<?php

namespace App\Enums\MarketingSms;

use App\Enums\BaseEnum;

enum TargetTypeEnum: string
{
    use BaseEnum;

    case CLUE = 'clue';
    case STUDENT = 'student';

    public function getLabel(): string
    {
        return __("marketing_sms_templates.target_type_{$this->value}");
    }

    public function getColor(): string
    {
        return match ($this) {
            self::CLUE => 'primary',
            self::STUDENT => 'info',
        };
    }
}
