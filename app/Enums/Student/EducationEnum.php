<?php

namespace App\Enums\Student;

use App\Enums\BaseEnum;

enum EducationEnum: string
{
    use BaseEnum;

    case UNDER_DIPLOMA = 'under_diploma';
    case DIPLOMA = 'diploma';
    case ASSOCIATE = 'associate';
    case BACHELOR = 'bachelor';
    case MASTER = 'master';
    case DOCTORAL = 'doctoral';

    public function getLabel(): string
    {
        return __("students.educations.{$this->value}");
    }
}
