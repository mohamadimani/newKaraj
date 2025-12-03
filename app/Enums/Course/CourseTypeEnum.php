<?php

namespace App\Enums\Course;

use App\Enums\BaseEnum;

enum CourseTypeEnum: string
{
    use BaseEnum;

    case PUBLIC = 'public';
    case PRIVATE = 'private';
}
