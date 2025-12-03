<?php

namespace App\Constants;

use App\Models\Student;

class RolePermission
{

    public static $roles = [
        'admin',
        'secretary',
        'teacher',
        'clue',
        'clerk',
    ];

    public static $clerkPermissions = [];

    public static $teacherPermissions = [];

    public static $secretaryPermissions = [];
}
