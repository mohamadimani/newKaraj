<?php

namespace App\Enums\Technical;

use App\Enums\BaseEnum;

enum StatusEnum: string
{
    use BaseEnum;

    case PROCESSING = 'processing';
    case INTRODUCED = 'introduced';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';
    case DONE = 'done';
}
