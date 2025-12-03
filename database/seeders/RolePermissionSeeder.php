<?php

namespace Database\Seeders;

use App\Constants\PermissionTitle;
use App\Constants\RolePermission;
use Illuminate\Database\Seeder;
use ReflectionClass;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = RolePermission::$roles;
        foreach ($roles as $role) {
            if (!Role::where('name', $role)->pluck('name')->first()) {
                Role::create([
                    'name' => $role,
                ]);
            }
        }

        $permissions = new ReflectionClass(PermissionTitle::class);
        $permissions = $permissions->getConstants();
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                ]);
            }
        }

        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->syncPermissions($permissions);

        // $clerkRole = Role::where('name', 'clerk')->first();
        // $clerkRole->syncPermissions(RolePermission::$clerkPermissions);

        // $secretaryRole = Role::where('name', 'secretary')->first();
        // $secretaryRole->syncPermissions(RolePermission::$secretaryPermissions);

        // $teacherRole = Role::where('name', 'teacher')->first();
        // $teacherRole->syncPermissions(RolePermission::$teacherPermissions);
    }
}
