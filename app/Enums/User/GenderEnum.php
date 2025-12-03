<?php

namespace App\Enums\User;

use App\Enums\BaseEnum;

enum GenderEnum: string
{
    use BaseEnum;

    case MALE = 'male';
    case FEMALE = 'female';
}
