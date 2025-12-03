<?php

namespace App\Enums\User;

use App\Enums\BaseEnum;

enum EducationEnum: string
{
    use BaseEnum;

    case UNDER_DIPLOMA = 'زیر دیپلم';
    case DIPLOMA = 'دیپلم';
    case ASSOCIATE_DEGREE = 'کاردانی';
    case BACHELORS = 'کارشناسی';
    case SENIOR = 'ارشد';
    case PHD = 'دکتری';
}
